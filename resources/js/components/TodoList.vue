<template>
    <!-- Main container with fluid width -->
    <v-container fluid>
        <!-- Flat card wrapper for consistent styling -->
        <v-card flat>
             <!-- Row for toolbar and search section -->
            <v-row>
                <v-col>
                     <!-- Toolbar with centered content and flat design -->
                    <v-toolbar flat>
                        <!-- Center aligned row for search and add button -->
                        <v-row align="center" justify="center">
                            <v-col cols="12" xl="2" lg="4" md="6" sm="8" xs="12">
                                <!-- Search field with responsive sizing -->
                                <v-text-field 
                                    v-model="strSearch"
                                    append-icon="mdi-magnify"
                                    label="Type here to search.."
                                    single-line
                                    solo
                                    hide-details
                                    dense
                                    outlined
                                ></v-text-field>
                            </v-col>
                            <!-- Spacer for right alignment of add button -->
                            <v-spacer/>
                             <!-- Add task button -->
                            <v-btn @click="(AddEditDialog = true),(blnAddUpdate = 0)">
                                <v-icon>mdi-plus</v-icon>
                            </v-btn>
                        </v-row>
                    </v-toolbar>
                </v-col>
            </v-row>

            <!-- Empty state display when no tasks exist -->
            <v-row v-if="!TaskList.length" align="center" justify="center">
                <v-col cols="12" xl="2" lg="4" md="6" sm="8" xs="12"  class="py-16">
                    <center>
                        <!-- Empty state message -->
                        <h1 style="color:rgb(202, 198, 198)">NO DATA AVAILABLE!</h1>
                    </center>
                </v-col>
            </v-row>
            <!-- Task list display when tasks exist -->
            <v-row v-else>
                <!-- Individual task card with responsive sizing -->
                <v-col cols="12" xl="2" lg="4" md="6" sm="8" xs="12"   v-for="(items,i) in cSearchFilter" :key="i">
                    <!-- Hover effect wrapper -->
                    <v-hover>
                        <template v-slot:default="{hover}">
                             <!-- Task card with dynamic elevation -->
                            <v-card
                                :elevation="hover ? 24 : 2"
                                color="#FAF0EF"
                            >
                                <!-- Card title with menu options -->
                                <v-card-title class="py-0">{{ items.TaskTitle }}
                                    <!-- Menu spacer -->
                                    <v-spacer></v-spacer>
                                     <!-- Options menu dropdown -->
                                    <v-menu offset-y close-on-content-click tile >

                                        <template v-slot:activator="{on, attrs}">
                                            <v-btn 
                                                fab 
                                                icon
                                                v-on="on"
                                                v-bind="attrs"
                                            >
                                                <v-icon>mdi-dots-vertical</v-icon>
                                            </v-btn>
                                        </template>

                                        <!-- Menu options -->
                                        <v-list class="cursor">
                                            <v-list-item>
                                                <v-list-item-title
                                                    @click="mEdit(items)"
                                        
                                                >EDIT</v-list-item-title>
                                            </v-list-item>
                                            <v-list-item>
                                                <v-list-item-title
                                                    @click="mDelete(items)"
                                                >DELETE</v-list-item-title>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>
                                </v-card-title>
                                <!-- Task description -->
                                <v-card-text>{{ items.Description }}</v-card-text>
                            </v-card>
                        </template>
                    </v-hover>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-pagination
                        class="pt-3"
                        v-model="pageNumber"
                        :length="cUserListpage"
                        :total-visible="5"
                        prev-icon="mdi-menu-left"
                        next-icon="mdi-menu-right"
                    ></v-pagination>
                </v-col>
            </v-row>
        </v-card>

        


        <!-- Add/Edit Dialog Template -->
        <template>
            <!-- Persistent dialog for adding/editing tasks -->
            <v-dialog v-model="AddEditDialog" max-width="400px" persistent>
                <v-card>
                    <!-- Dialog header with dynamic title -->
                    <v-card-title style="background-color: #3C3C46;" class="py-0 white--text">{{ blnAddUpdate == -1 ? 'Edit To-Do' : 'Add To-Do' }}
                        <!-- Close button -->
                        <v-spacer/>
                        <v-btn fab icon>
                            <v-icon color="white" @click="mCloseDialog()">mdi-close</v-icon>
                        </v-btn>
                    </v-card-title>
                    <!-- Form inputs -->
                    <v-card-text class="pb-0 pt-2" >
                        <v-row no-gutters>
                            <v-col  cols="12">
                                 <!-- Title field -->
                                <v-text-field
                                    v-model="objData.TaskTitle"
                                    dense
                                    outlined
                                    label="To-Do Title"
                                />
                            </v-col>
                            <v-col  cols="12">
                                <!-- Description textarea -->
                                <v-textarea
                                    v-model="objData.Description"
                                    filled
                                    auto-grow
                                    label="Description"
                                />
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <!-- Action buttons -->
                    <v-card-actions class="pt-0">
                        <v-spacer/>
                        <v-btn @click="mSave()">SAVE</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </template>
    </v-container>
</template>

<script>
import Swal from 'sweetalert2'
export default {
    data(){
        return{
            TaskList: [],
            AddEditDialog: false,
            blnAddUpdate: -1,
            objData: {},
            strSearch: '',
            pageNumber: 1,
        }
    },

    created() {
        // Initializes the task list by fetching data from API
        this.mgetTaskList()
    },

    watch: {
        
    },

    computed: {
         // Filters task list based on search criteria
        cSearchFilter(){
            return this.cUserList.filter(rec =>{
                if(this.strSearch){
                    return rec.TaskTitle.toUpperCase().includes(this.strSearch.toUpperCase()) ||
                    rec.Description.toUpperCase().includes(this.strSearch.toUpperCase())
                }else{
                    return rec;
                }
            })
        },

        // Returns paginated subset of task list
        cUserList(){
            const start = (this.pageNumber - 1) * 12, end = start + 12;
            return this.TaskList.slice(start, end);
        },
        
        // Calculates total number of pages based on task count
        cUserListpage(){
            let length = this.TaskList.length
            return Math.ceil(length/12);

            
        },
    },

    methods: {
         // Fetches task list from API endpoint
        mgetTaskList(){
            axios.get('api/task')
            .then((res)=>{
                this.TaskList = res.data
                
            })
        },

        // Prepares edit dialog with selected task data
        mEdit(items){
            this.blnAddUpdate = -1
            this.AddEditDialog = true
            this.objData.TaskTitle = items.TaskTitle
            this.objData.Description = items.Description
            this.objData.TaskID = items.TaskID

            
        },

        // Resets dialog state and closes it
        mCloseDialog(){
            this.objData = {}
            this.AddEditDialog = false
        },

        // Handles saving new or updated tasks
        mSave(){
            // Validates required fields
            if(!this.objData.TaskTitle  || !this.objData.Description){
                    Swal.fire({
                        text: "Please fill out Title/Description",
                        showConfirmButton: false,
                        icon: 'info',
                        timer: 3000
                    });
                    
                }else{
                    if(this.blnAddUpdate == 0){
                        this.objData.method = 'ADD'
                            axios.post('api/task', this.objData)
                            .then((res) =>{
                                if(res.data == 'Inserted Successfully'){
                                    this.mgetTaskList()
                                    this.mCloseDialog()
                                    Swal.fire({
                                        title: res.data,
                                        showConfirmButton: false,
                                        icon: 'success',
                                        timer: 3000
                                    });
                                }else{
                                    this.mCloseDialog()
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Something happens upon saving',
                                        showConfirmButton: false,
                                        icon: 'error',
                                        timer: 3000
                                    });
                                }
                            })
                    }else{
                         // Update existing task
                        this.objData.method = 'EDIT'
                        axios.put('api/task/update',this.objData)
                        .then((res) => {
                            if(res.data = 'Updated Successfully'){
                                this.mgetTaskList()
                                this.mCloseDialog()
                                Swal.fire({
                                    title: res.data,
                                    showConfirmButton: false,
                                    icon: 'success',
                                    timer: 3000
                                });
                            }else{
                                this.mCloseDialog()
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Something happens upon saving',
                                    showConfirmButton: false,
                                    icon: 'error',
                                    timer: 3000
                                });
                            }
                            
                        })
                    }
                }
        },

        // Deletes a task with confirmation prompt
        mDelete(items){
            Swal.fire({
                title: "Are you sure?",
                text: "User will be deleted",
                icon: "warning",
                showCancelButton: true,
                cancelButtonText: "<span style='color: white;'>NO</span>",
                confirmButtonText: "<span style='color: white;'>YES</span>",
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
            }).then((res) =>{
                if(res.isConfirmed){
                    items.method = 'DELETE'
                    axios.put('api/task/update',items)
                    .then((res) => {
                        if(res.data = 'Deleted Successfully'){
                            this.mgetTaskList()
                            Swal.fire({
                                title: res.data,
                                showConfirmButton: false,
                                icon: 'success',
                                timer: 3000
                            });
                        }else{
                            Swal.fire({
                                title: 'Error',
                                text: 'Something happens upon saving',
                                showConfirmButton: false,
                                icon: 'error',
                                timer: 3000
                            });
                        }
                        
                    })
                  
                }
               
            })
            
        }
    },
}
</script>


<style>
.cursor {
  cursor: pointer;
}

/* .v-pagination {
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: transparent;
    z-index: 2;
} */

</style>