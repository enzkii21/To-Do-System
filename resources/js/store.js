import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex)
export default new Vuex.Store({
    state:{
        UserList:{},
        session: false,
    },
    actions:{},
    mutations:{
        Login_Info(state,data){
            state.UserList = data
            state.session = true
        },

        Logout(state) {
            state.UserList = {}
            state.session = false
        },
    },
    getters:{},
    plugins: [createPersistedState()]
});