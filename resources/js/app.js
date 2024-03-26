import './bootstrap';
import Aos from 'aos';
import 'aos/dist/aos.css'

Aos.init({
    duration: 1000,
    easing: 'ease-in-out',
    once: true,
    mirror: false
});

import { createApp } from 'vue';
import App from './components/App.vue'

import { createRouter,createWebHashHistory } from 'vue-router';

const app = createApp({})

app.component('app-component', App)

const routes = [
    {
        path: '/',
        component: ()=>import('./components/accueil.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/vente',
        component: ()=>import('./components/vente.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/login',
        component: ()=>import('./components/login.vue')
    }
]
const router = createRouter({
    history: createWebHashHistory(),
    routes
})

//Pour la connexion
function requireAuth(to, from, next){
    const isAuthenticated = localStorage.getItem('token')
    if (isAuthenticated) {
        next();
    }else{
        next('/login')
    }
}

router.beforeEach((to, from, next)=>{
    if (to.meta.requiresAuth) {
        requireAuth(to, from, next);
    }else{
        next()
    }
})
//Fin pour la connexion



app.use(router)


app.mount('#app')