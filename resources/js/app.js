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

const app = createApp({})

app.component('app-component', App)


app.mount('#app')