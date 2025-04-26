<template>
   <!-- Root application container with unique identifier -->
    <v-app id="inspire">
      <!-- 
      Navigation Drawer Component
      =========================
      A sliding panel that contains navigation links and logout option.
      Conditionally rendered based on authentication status.
    -->
        <v-navigation-drawer
        v-if="cMyCredentials"
         v-model="drawer"
         color="#3C3C46"
         dark
         app>
         <!-- Dense list for compact navigation items -->
           <v-list dense>
             <!-- Header for navigation items section -->
             <v-subheader>LIST COMPONENTS:</v-subheader>
              <!-- Dynamic navigation items generated from items array -->
               <v-list-item
                v-for="(item, i) in items"
                :key="i"
                :to="item.to">
                 <!-- Icon display for each navigation item -->
                  <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                  </v-list-item-icon>
                  <!-- Content section for navigation item -->
             <v-list-item-content>
                        <v-list-item-title>{{ item.text }}</v-list-item-title>
                     </v-list-item-content>
                 </v-list-item>
            </v-list>
            <!-- Footer slot for logout functionality -->
            <template v-slot:append>
               <div class="pa-2">
                  <!-- Logout button triggering dialog -->
                  <v-btn color="#CEC4C2" light block @click="mLogoutDialog()"> Logout </v-btn>
               </div>
            </template>
         </v-navigation-drawer>
         <!-- Application Bar Component
         =========================
         Top navigation bar with drawer toggle and application title.
         Only visible when authenticated. -->
         <v-app-bar app v-if="cMyCredentials" color="#CEC4C2">
             <!-- Toggle button for navigation drawer -->
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <!-- Application title display -->
            <v-toolbar-title>My To-Do List</v-toolbar-title>
         </v-app-bar>

          <!-- Main Content Area
         ===================
         Container for dynamic route rendering with margin spacing -->
         <v-main class="ma-2">
            <!-- Router view renders current route component -->
            <router-view></router-view>
         </v-main>
     </v-app>
</template>
   
<script>
import Swal from 'sweetalert2'
export default {
   data: () => ({
      drawer: null,
      items:[
         {text:'To-Do List', icon:'mdi-clipboard-outline', to:'todo_list'}
      ]
   }),

   computed:{
      //checks if a user is logged in the Vuex store
      cMyCredentials(){
         return this.$store.state.UserList.Username != undefined;
      }
   },

   methods: {
      //Logging out of account
      mLogoutDialog() {
         Swal.fire({
            title: "Are you sure?",
            text: "You wil be logged out",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "<span style='color: white;'>NO</span>",
            confirmButtonText: "<span style='color: white;'>YES</span>",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
         }).then((result) => {
            if (result.isConfirmed) {
               this.mLogout();
            }
         });
    },
      //clears the Vuex store 
      mLogout() {
         if (this.$route.path != "/") {
            this.$router.push("/");
         }
         this.$store.commit("Logout");
      },
   },
}
</script>