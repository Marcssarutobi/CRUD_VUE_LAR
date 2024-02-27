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
        component: ()=>import('./components/accueil.vue')
    },
    {
        path: '/vente',
        component: ()=>import('./components/vente.vue')
    }
]
const router = createRouter({
    history: createWebHashHistory(),
    routes
})

app.use(router)


app.mount('#app')