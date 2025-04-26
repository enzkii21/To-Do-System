<template>
     <!-- Full height container that centers content vertically and horizontally -->
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
             <!-- Dark themed card with elevated shadow effect -->
            <v-card 
                elevation="20"
                class="mx-auto pt-3 pb-3 pr-3 pl-3"
                width="600"
                color="#333333">
                 <!-- Grid row for splitting content into two columns -->
                <v-row no-gutters >
                      <!-- Left column containing the brand image -->
                    <v-col
                    cols="6"
                    md="6"
                    sm="12"
                    xs="12"
                    align="center">
                        <v-row>
                            <v-col align-center justify-center>
                                 <!-- Brand logo/image display -->
                                <v-img
                                    max-height="300"
                                    max-width="300"
                                    src="images/Checklist.png"
                                >

                                </v-img>
                            </v-col>
                        </v-row>
                    </v-col>
                    <!-- Right column containing the login form -->
                    <v-col
                    cols="6"
                    md="6"
                    sm="12"
                    xs="12"
                    align="center">
                        <br/>
                         <!-- Dark theme wrapper for form elements -->
                        <template dark>
                             <!-- Large title text for the application -->
                            <span  class="white--text" style="font-size: 30px">My To-Do Lists</span>
                            <!-- Username input field with validation -->
                            <v-text-field
                                background-color="#F5F5F5"
                                v-model="username"
                                color="success"
                                label="Username"
                                @keydown="mPreventSpace($event,username)"
                                dense
                                filled
                                outlined
                                prepend-inner-icon="mdi-account"
                            />
                             <!-- Password input field with show/hide functionality -->
                            <v-text-field
                                background-color="#F5F5F5"
                                color="success"
                                v-model="password"
                                label="Password"
                                @keydown="mPreventSpace($event,password)"
                                :append-icon="showPass ? 'mdi-eye' : 'mdi-eye-off'"
                                @click:append="showPass = !showPass"
                                :type="showPass ? 'text' : 'password'"
                                filled
                                dense
                                outlined
                                prepend-inner-icon="mdi-key"
                            />
                        </template>
                         <!-- Action buttons section -->
                        <v-card-actions class="pt-8">
                            <v-spacer/>
                            <!-- Login action button -->
                            <v-btn @click="mLogin()">Login</v-btn>
                        </v-card-actions>
                    </v-col>
                </v-row>
            </v-card>
        </v-layout>
    </v-container>
</template>

<script>
import Swal from 'sweetalert2'
export default {
    data(){
        return{
            username: '',
            password: '',
            user: [],
            showPass: false,
        }
    },

    methods: {
        //Getting users based on username and password
        mLogin(){
           axios.get(`api/userlist?username=${this.username}&&password=${this.password}`)
           .then((res)=>{
                //Username and Password not match or found
                if(res.data == 'No Data Found!'){
                    Swal.fire({
                        text: "Username or Password not found",
                        showConfirmButton: false,
                        icon: 'error',
                        timer: 3000
                    }).then(()=>{
                        this.username = ''
                        this.password = ''
                    });
                }
                //checking for blank textfield
                else if(res.data == 'Empty Text'){
                    Swal.fire({
                        text: "Please fill out Username/Password",
                        showConfirmButton: false,
                        icon: 'info',
                        timer: 3000
                    })
                }
                //Username and Password match
                else{
                    this.user = {
                        UserID: res.data[0].UserID,
                        Username: res.data[0].Username,
                        Password: res.data[0].Password,
                        FirstName: res.data[0].FirstName,
                        MiddleName: res.data[0].MiddleName,
                        LastName: res.data[0].LastName
                    }
                    Swal.fire({
                          title: 'Welcome ' +  this.user.FirstName,
                          text: 'You are successfully logged in!',
                          showConfirmButton: false,
                          icon: 'success',
                          timer: 2000
                        }).then(()=>{
                            //storing the user info in local storage
                          this.$store.commit('Login_Info', this.user)
                          //push route to 'todo_list'
                          this.$router.push('/todo_list')
                        })
                }
           })
           
        },

        //preventing of space in textfield
        mPreventSpace(e,data){
            if (e.key === ' ' && data.length === 0) {
                e.preventDefault();
                return false;
            }
        },
    },
}
</script>