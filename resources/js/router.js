import Vue from 'vue'
import VueRouter from 'vue-router'
import TodoList from './components/TodoList'
import Login from './template/Login.vue'
import store from './store';

Vue.use(VueRouter)


let router =  new VueRouter({
    mode: 'history',
    base: '/',
    routes: [
        {
            path: '/todo_list',
            name: 'todo_list',
            component: TodoList
        },
        {
            path: '/',
            name: 'login',
            component: Login,
            beforeEnter(to,from,next){
                if(store.state.session){
                    // alert("LOGGING OUT!")
                    store.commit("Logout");
                    next()
                }else{
                    next()
                }
            }
        
        },
    ],
});
router.beforeEach((to, from, next) => {
    if (!store.state.session  && to.name !== 'login')
        next({ name: 'login' });
    else next();
});
export default router
