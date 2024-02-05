<template>
  <div class="container mt-4">

    <h2 class=" fw-bold ">Effectuer une vente</h2>

    <div class="select_data">
        <div class="input">
            <input type="text" placeholder="Selectionnez un produits" v-model="search" @keyup="searchBlog">
            <i class="fa fa-angle-down" aria-hidden="true" @click="ShowSelect"></i>
        </div>
        <div v-if="select" class="data" data-aos="fade-down" data-aos-duration="300">
            <div class="carte" v-for="prod in allProd" :key="prod.id" @click="GetProduit(prod.id)">
                <div class="info">
                    <span class="produit">{{ prod.name }}</span>
                    <span class="prix">{{ prod.Pvente }} FCFA</span>
                </div>
                <i class="fa fa-plus" aria-hidden="true" @click="GetProduit(prod.id)"></i>
            </div>
            <p v-if="vide" class="mt-4" >Ce produits n'est  pas disponible</p>
        </div>
    </div>

    <div class="tableau">
        <table class="table">
            <thead class=" table-dark ">
                <tr>
                    <th scope="col">Produits</th>
                    <th scope="col">Quantité</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Prix Total</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="pan in panier" :key="pan.id">
                    <th>{{ pan.name }}</th>
                    <td style="display: flex; align-items: center; gap: 8px;">
                        <button class="btn btn-light " style="font-size: 18px; font-weight: 900;" @click="decreaseQte(pan)">-</button>
                        <input type="text" style="width: 50px;" disabled v-model="pan.qte"/>
                        <button class="btn btn-light " style="font-size: 18px; font-weight: 900;" @click="increaseQte(pan)">+</button>
                    </td>
                    <td>{{ pan.Pvente }} FCFA</td>
                    <td>{{ pan.total }} FCFA</td>
                    <td><button class="btn btn-light " @click="removeFromCart(pan)"> <i class="fa fa-trash" aria-hidden="true"></i> </button></td>
                </tr>
            </tbody>
        </table>
    </div>

  </div>
</template>

<script>
import axios from 'axios'
export default {
    data(){
        return {
            allProd: {},
            select: false,
            search:"",
            getProd:{},
            vide: false,
            panier:[],
            panierId:[],
            ProTotal: 0
        }
    },
    methods:{
        async AllProduits(){
            const res = await axios.get('/Produits')
            if (res.status === 200) {
                this.allProd =res.data.produits
            }
        },
        ShowSelect(){
            this.select = !this.select
            
        },
        async searchBlog(){
            if (this.search.length > 0) {
                this.select = true
                const res = await axios.get('/searchPrduits/' + this.search)
                if (res.status === 200) {
                    this.allProd = res.data.produit
                    if (this.allProd.length === 0) {
                        this.vide = true
                    }
                }
            }else{
                this.vide = false
                this.select = false
                this.AllProduits()
            }
        },
        async GetProduit(id){
            this.EditModal = true
            const res = await axios.get("/getProduit/" + id)
            if (res.status === 200) {
                this.getProd = res.data.produit
                this.getProd.qte = 1 // initialiser la valeur de la quantité à 1
                this.getProd.total = this.getProd.Pvente * this.getProd.qte //Calculer pour trouver le prix total
                
                if (!this.panierId.includes(this.getProd.id)) {

                    this.panier.push(this.getProd)
                    this.panierId.push(this.getProd.id)

                    this.ProTotal += this.getProd.total //Enrégistrer le total

                }
                this.select =false
                console.log(this.ProTotal)
            }
        },
        decreaseQte(product){ // Décrementer
            if (product.qte > 1) {
                product.qte--
                product.total = product.Pvente * product.qte
            }
        },
        increaseQte(product){ //Incrémenter
            product.qte++
            product.total = product.Pvente * product.qte
        },
        removeFromCart(product) { //Supprimer du tableaux
            const index = this.panier.indexOf(product);
            if (index !== -1) {
                this.panier.splice(index, 1);
            }
        },

    },
    created(){
        this.AllProduits()
    }
}
</script>

<style>
td,th{
    width: 259.19px;
}
</style>