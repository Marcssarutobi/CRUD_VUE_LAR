<template>
  <div class="body">

    <div class="main">

        <div class="sigin-signup">

            <form class="sign-in-form" @submit.prevent="Connexion">
                <h2 class="title">Sign In</h2>
                <div class="input-field">
                    <i class='fas fa-user'></i>
                    <input type="text" placeholder="E-mail" v-model="data_connexion.email">
                </div>
                <div class="input-field">
                    <i class='fas fa-lock'></i>
                    <input type="password" placeholder="Password" v-model="data_connexion.password">
                </div>
                <input type="submit" value="Login" class="btns">

                <p class="account-text">Don't have an account ? <a href="#" id="sign-up-btn2">Sign up</a></p>
            </form>

            <form class="sign-up-form" @submit.prevent="Inscription">
                <h2 class="title">Sign Up</h2>
                <div class="input-field">
                    <i class='fas fa-user'></i>
                    <input type="text" placeholder="Username" v-model="data_inscription.name">
                </div>
                <div class="input-field">
                    <i class='fas fa-envelope' ></i>
                    <input type="email" placeholder="E-mail" v-model="data_inscription.email">
                </div>
                <div class="input-field">
                    <i class='fas fa-lock'></i>
                    <input type="password" placeholder="Password" v-model="data_inscription.password" >
                </div>
                <input type="submit" value="Sign Up" class="btns">

                <p class="account-text">Already have an account ? <a href="#" id="sign-in-btn2">Sign in</a></p>
            </form>

        </div>

        <div class="panels-container">

            <div class="panel left-panel">
                <div class="content">
                    <h1>Wellcome Back !</h1>
                    <p>Please login with your personal info</p>
                    <button class="btns" id="sign-in-btn">Sign in</button>
                </div>
            </div>

            <div class="panel right-panel">
                <div class="content">
                    <h1>Hello, Friend</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button class="btns" id="sign-up-btn">Sign up</button>
                </div>
            </div>
        </div>

    </div>

  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2'

export default {
    mounted(){
        const sign_in_btn = document.querySelector('#sign-in-btn')
        const sign_up_btn = document.querySelector('#sign-up-btn')
        const main = document.querySelector('.main')
        const sign_in_btn2 = document.querySelector('#sign-in-btn2')
        const sign_up_btn2 = document.querySelector('#sign-up-btn2')

        sign_up_btn.addEventListener('click',function(){
            main.classList.add('sign-up-mode')
        })
        sign_in_btn.addEventListener('click',function(){
            main.classList.remove('sign-up-mode')
        })
        sign_up_btn2.addEventListener('click',function(){
            main.classList.add('sign-up-mode2')
        })
        sign_in_btn2.addEventListener('click',function(){
            main.classList.remove('sign-up-mode2')
        })
    },
    data(){
        return{
            data_inscription:{
                name:'',
                email:'',
                password:''
            },
            data_connexion:{
                email:'',
                password:''
            },
            loginUser:{}
        }
    },
    methods:{
        async Inscription(){
            //Vérification des champs
            if (this.data_inscription.name.trim() == "" || this.data_inscription.email.trim() == "" ||  this.data_inscription.password.trim() == "") {
                
                Swal.fire({
                    toast: true,
                    position:"top-end",
                    title:"Veuillez remplir tous les champs",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon:"error",
                })

            }else{
                try {
                    
                    const res = await axios.post('/inscription',this.data_inscription)
                    if (res.status === 200) {

                        Swal.fire({
                            toast: true,
                            position:"top-end",
                            title:"Utilisateur Enrégistrer avec succes",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon:"success",
                        })
                        this.data_inscription.name = ''
                        this.data_inscription.email = ''
                        this.data_inscription.password = ''

                    }

                } catch (error) {
                    console.log(error)
                }
            }
        },
        async Connexion(){
            try {
                
                const res = await axios.post('/connexion',this.data_connexion)
                
                if (res.status === 200) {
                    localStorage.setItem('token',res.data.Token)
                    this.loginUser = res.data.User

                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        title: "Connexion Réussie",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: "success"
                    })
                    this.data_connexion.email = ""
                    this.data_connexion.password = ""

                    this.$router.push('/');

                    
                }

            } catch (error) {
                console.error(error)
            }
        }
    }
}
</script>

<style scoped>
.body{
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    font-family: 'Montserrat', sans-serif;
    color: #000;
}
.main{
    position: relative;
    width: 70vw;
    height: 80vh;
    background: #1d1b31;
    border-radius: 20px;
    box-shadow: 0 0 28px rgba(0, 0, 0, .25),
                0 0 10px rgba(0, 0, 0, .22);
    overflow: hidden;
}
.main::before{
    content: "";
    position: absolute;
    top: 0;
    left: -50%;
    width: 100%;
    height: 100%;
    background: #11101d;
    z-index: 6;
    transform: translateX(100%);
    transition: 1s ease-in-out;
}
.sigin-signup{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: space-around;
    z-index: 5;
}
form{
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    width: 40%;
    min-width: 238px;
    padding: 0 10px;
}
form.sign-in-form{
    opacity: 1;
    transition: 0.5s ease-in-out;
    transition-delay: 1s;
}
form.sign-up-form{
    opacity: 0;
    transition: 0.5s ease-in-out;
    transition-delay: 1s;
}
.title{
    font-size: 35px;
    color: #fff;
    margin-bottom: 10px;
}
.input-field{
    width: 100%;
    height: 50px;
    background: #11101d;
    margin: 10px 0;
    border: none;
    border-radius: 50px;
    display: flex;
    align-items: center;
}
.input-field i{
    flex: 1;
    text-align: center;
    color: #fff;
    font-size: 18px;
}
.input-field input{
    flex: 5;
    background: transparent;
    color: #fff;
    border: none;
    outline: none;
    width: 100%;
    height: 100%;
    font-size: 18px;
    font-weight: 600;
}
.btns{
    width: 150px;
    height: 50px;
    border: 1px solid #fff;
    border-radius: 50px;
    background: #11101d;
    color: #fff;
    font-weight: 600;
    margin: 10px 0;
    text-transform: uppercase;
    cursor: pointer;
    transition: transform 0.6s ease-in-out;
}
.btns:active{
    transform: scale(0.90);
}
.panels-container{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: space-around;
}
.panel{
    display: flex;
    flex-direction: column;
    align-items: center;
    z-index: 6;
    width: 35%;
    min-width: 238px;
    text-align: center;
    padding: 0 10px;
}
.content{
    color: #fff;
    transition: 1.1s ease-in-out;
    transition-delay: 0.5s;
}
.panel h1{
    font-weight: 600;
}
.panel  p{
    font-size: 18px;
    padding: 10px 0;
}

.left-panel .content{
    transform: translateX(-200%);
}
.right-panel .content{
    transform: translateX(0);
}
.account-text{
    display: none;
}

/*Animation */
.main.sign-up-mode::before{
    transform: translateX(0);
}
.main.sign-up-mode .right-panel .content{
    transform: translateX(200%);
}
.main.sign-up-mode .left-panel .content{
    transform: translateX(0);
}
.main.sign-up-mode form.sign-in-form{
    opacity: 0;
}
.main.sign-up-mode form.sign-up-form{
    opacity: 1;
}
</style>