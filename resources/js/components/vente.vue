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
        <div class="paniervide" v-if="panier.length === 0">
            <span>Panier vide</span>
            <i class="fas fa-bucket" aria-hidden="true"></i>
        </div>
        <div class="totale">
            <div></div>
            <div class="prix">
                <span>{{ ProTotal }} FCFA</span>
            </div>
        </div>
    </div>
    <div class="button">
        <div></div>
        <button class="btn btn-primary btn-lg rounded-0  " @click="ValidateAchat" :class="panier.length === 0 ? ' disabled ':''">Enrégistrer l'achats</button>
    </div>

    <!-- Pdf -->
    <div  class="pdf" ref="invoiceContent">

        <h3>Reçue d'achats</h3>

        <table class="table">
            <thead>
                <tr>
                <th scope="col">Nom Prouits</th>
                <th scope="col">Quantité</th>
                <th scope="col">Prix de vente</th>
                <th scope="col">Prix totale</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="pan in panier" :key="pan.id">
                    <td>{{ pan.name }}</td>
                    <td>{{ pan.qte }}</td>
                    <td>{{ pan.Pvente }}</td>
                    <td>{{ pan.total }}</td>
                </tr>
            </tbody>
        </table>

        <div class="Totale">
            <div></div>
            <div class="prix">
                <span>Montant Totale : {{ ProTotal }} FCFA</span>
            </div>
        </div>

    </div>

    

  </div>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import html2pdf from 'html2pdf.js'
export default {
    data(){
        return {
            allProd: {},
            select: false,
            search:"",
            getProd:{}, //Récupère le produits
            vide: false,
            panier:[], // Panier des produits
            panierId:[], // Panier id Produits
            ProTotal: 0, // Somme totale
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
        updateProTotale(){ // Fais le calcule totale des prix total
            if(this.panier.length === 0){
                this.ProTotal = 0
            }else{
                this.ProTotal = this.panier.reduce((total,product)=> total + product.total, 0)//Enrégistrer le total
            }
            console.log(this.ProTotal)
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

                    this.updateProTotale() //Enrégistrer le total

                }
                this.select =false
                this.search = ""
                this.AllProduits()
            }
        },
        decreaseQte(product){ // Décrementer
            if (product.qte > 1) {
                product.qte--
                product.total = product.Pvente * product.qte
                this.updateProTotale() 
            }
        },
        increaseQte(product){ //Incrémenter
            product.qte++
            product.total = product.Pvente * product.qte
            this.updateProTotale() 
        },
        removeFromCart(product) { //Supprimer du tableaux
            const index = this.panier.indexOf(product);
            if (index !== -1) {
                this.panier.splice(index, 1);
                this.updateProTotale()
            }
        },
        async ValidateAchat(){ //Envoie dans la base de donner

            //D'abord récupérer chaque élément du panier et l'affècter à la colonne de la table
            const response = await Promise.all(
                this.panier.map(async (prod) => {
                    const venteData = {
                        ProductId: prod.id,
                        QteAchat:prod.qte,
                        PTotal:prod.total
                    }

                    const res = await axios.post('/createVente',venteData);
                    if (res.status === 200) {
                        await this.updateStock(prod.id, prod.qte)
                    }

                    return res
                })
            )
            
            // Renvoie un message de succès si tout est bien enrégistrer
            const success = response.every((res)=> res.status === 200)
            if (success) {
                Swal.fire({
                    position:'center',
                    icon:'success',
                    title:'Achats valide',
                    showConfirmButton: false,
                    timer: 1500
                })
                
                //Générer le pdf
                const invoiceContent = this.$refs.invoiceContent
                const option = {
                    margin:0,
                    filename: 'document.pdf',
                    image:{type:'jpeg',quality: 0.98},
                    html2canvas:{scale:2},
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait', },
                }
                html2pdf(invoiceContent,option)


                //Initialisation après validation
                this.panier = []
                this.panierId = []
                this.ProTotal = 0
                
            }
           
        },
        async updateStock(ProduitId,QteSold){
            //Récupérer chaque id du panier
            const produits = await axios.get('/getProduit/' + ProduitId)
            const currentStock = produits.data.produit //le stocker dans une variable
            
            currentStock.qte = currentStock.qte - QteSold //Calculer la quantiter restante
            const res = await axios.post('/update_Produit', currentStock) //Modifier le produits
            if(res.status === 200){
                console.log('OK OK')
            }
        }

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