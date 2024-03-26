<template>

    <div>
        <nav v-if="navbar" class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">GesSarl</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <router-link class="nav-link fw-bold" to="/">Accueil</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link fw-bold" to="/vente">Vente de produits</router-link>
                        </li>
                        <li class="nav-item">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{LoginUser.name}}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><span class="dropdown-item" @click="Logout">Deconnexion</span></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <router-view/>
    </div>
  
</template>

<script>
import axios from 'axios';
import accueil from './accueil.vue';
export default {
    components:{
        accueil
    },
    computed:{
        navbar(){
            return this.$route.path !== '/login'
        }
    },
    data(){
        return {
            LoginUser:{}
        }
    },
    methods:{
        async Logout(){
            try {
                await axios.post('/deconnexion',null,{
                    headers:{
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    }
                })
                localStorage.removeItem('token')
                this.$router.push("/login")

            } catch (error) {
                console.error(error)
            }
        }
    },
    async created(){
        const res = await axios.get('/enLigne')
        if (res.status === 200) {
            this.LoginUser = res.data.user
        }
    }
}
</script>

<style>

</style>