<template>
  <div>



    <div class="container">

        <div class="search">
            <i class="fa fa-search"></i>
            <input type="search" placeholder="Rechercher...">
            <button>Rechercher</button>
        </div>


        <div class="tableaux">

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">N° Produit</th>
                        <th scope="col">Nom du Produit</th>
                        <th scope="col">Catégorie</th>
                        <th scope="col">Quantité</th>
                        <th scope="col">Prix Achats</th>
                        <th scope="col">Prix Vente (Unité)</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class=" table-group-divider ">
                    <tr v-for="prod in allProd" :key="prod.id">
                        <th scope="row">{{prod.id}}</th>
                        <td>{{ prod.name }}</td>
                        <td>{{ GetCategoryName(prod.categorie_id) }}</td>
                        <td>{{ prod.qte }}</td>
                        <td>{{prod.Pachats}} FCFA</td>
                        <td>{{prod.Pvente}} FCFA</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h" ></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><span class="dropdown-item" @click="GetProduitView(prod.id)" > <i class="fas fa-file-lines" ></i> Afficher</span></li>
                                    <li><span class="dropdown-item" @click="GetProduitEdit(prod.id)" ><i class="fas fa-pen"></i> Modifier</span></li>
                                    <li><span class="dropdown-item" @click="GetProdDel(prod.id)" > <i class="fas fa-trash" ></i> Supprimer</span></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <nav aria-label="...">
                <ul class="pagination">
                    <li class="page-item" :class="currentPage === 1 ? 'disabled ':''">
                        <span class="page-link" role="button" @click="allProduits(currentPage - 1)">Previous</span>
                    </li>
                    <li class="page-item" v-for="page in totalPage" :key="page" :class="page === currentPage ? 'active':''">
                        <span class="page-link" @click="allProduits(page)" role="button">{{ page }}</span>
                    </li>
                    <li class="page-item" :class="currentPage === totalPage ?'disabled':''">
                        <span class="page-link" role="button" @click="allProduits(currentPage + 1)">Next</span>
                    </li>
                </ul>
            </nav>

        </div>

        <!-- Modal Enrégistrement -->
        <div v-if="addModal" class="modal fade show" tabindex="-1" style="display: block; background: rgba(0, 0, 0, .5);">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content" data-aos="zoom-in" data-aos-duration="300">
                    <div class="modal-header">
                        <h5 class="modal-title">Enrégistrement de produits</h5>
                        <button type="button" class="btn-close" @click="ModalFermer" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        


                        <div class="image mb-3">

                            <label for="img">
                                <input type="file" id="img" hidden @change="handleFileImg">
                                <i class="fa fa-cloud-upload-alt"></i>
                                <p>Télécharger une image</p>
                            </label>

                            <section class="progress-area" v-if="ShowProgress">
                                <li class="rows">

                                    <i class="fas fa-file-alt" ></i>
                                    <div class="content">
                                        <div class="details">
                                            <span class="name">{{ names }} ~ Téléchargement...</span>
                                            <div class="percent">{{ percent }}%</div>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress" :style="'width:'+ percent + '%'"></div>
                                        </div>
                                    </div>

                                </li>
                            </section>
                            <section class="uploaded-area" v-if="ShowUploded">
                                <li class="rows">
                                    <div class="content">
                                        <i class="fas fa-file-alt" ></i>
                                        <div class="details">
                                            <span class="name">{{names}} ~ Télécharger</span>
                                            <div class="size">{{size}}</div>
                                        </div>
                                    </div>
                                    <i class="fas fa-check"></i>
                                </li>
                            </section>

                        </div>
                        <div class="form-group mb-3">
                            <label for="">Nom du produits :</label>
                            <input type="text" class="form-control" v-model="data.name">
                        </div>
                        <div class="rows_cat">
                            <div class="form-group mb-3 cat">
                                <label for="">Catégorie :</label>
                                <select class="form-select" v-model="data.categorie_id">
                                    <option selected disabled>Selectionnez une catégorie</option>
                                    <option v-for="cat in allCat" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                            </div>
                            <button class="btn btn-primary mt-2" @click="cat"> <i class="fa fa-plus" aria-hidden="true"></i> </button>
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Quantité :</label>
                            <input type="number" class="form-control" v-model="data.qte">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Prix Achat :</label>
                            <input type="number" class="form-control" v-model="data.Pachats">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Prix Vente (Unité) :</label>
                            <input type="number" class="form-control" v-model="data.Pvente">
                        </div>



                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="ModalFermer" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" @click="addProduits">Enrégistrer</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin Modal Enrégistrement -->
        <!-- Modal catégorie -->
        <div v-if="addcat" class="modal fade show" tabindex="-1" style="display: block; background: rgba(0, 0, 0, .5);">
            <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content" data-aos="zoom-in" data-aos-duration="300">
                    <div class="modal-header">
                        <h5 class="modal-title">Nouvelle catégorie</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" @click="cat" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="">Nom de la catégorie :</label>
                            <input type="text" class="form-control" v-model="cats.name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="cat">Revenir en arrière</button>
                        <button type="button" class="btn btn-primary" @click="AddCategorie">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin Modal catégorie -->
        <!-- Modal Affichage -->
        <div v-if="ViewModal" class="modal fade show" tabindex="-1" style="display: block; background: rgba(0, 0, 0, .5);">
            <div class="modal-dialog modal-dialog-centered modal-xl ">
                <div class="modal-content" data-aos="zoom-in" data-aos-duration="300">
                    <div class="modal-header">
                        <h5 class="modal-title">Produits</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" @click="ViewModal = false" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="contenue">
                            <div class="image">
                                <img :src="getProd.image" alt="">
                            </div>
                            <div class="information">
                                <div class="info">
                                    <span class="title">Nom du Produit : </span>
                                    <span class="data">{{ getProd.name }}</span>
                                </div>
                                <div class="info">
                                    <span class="title">Catégorie : </span>
                                    <span class="data">{{ GetCategoryName(getProd.categorie_id) }}</span>
                                </div>
                                <div class="info">
                                    <span class="title">Quantité : </span>
                                    <span class="data">{{ getProd.qte }}</span>
                                </div>
                                <div class="info">
                                    <span class="title">Prix Achats : </span>
                                    <span class="data">{{ getProd.Pachats }} FCFA</span>
                                </div>
                                <div class="info">
                                    <span class="title">Prix Vente : </span>
                                    <span class="data">{{ getProd.Pvente }} FCFA</span>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="ViewModal = false" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin Modal Affichage -->
        <!-- Modal Edit -->
        <div v-if="EditModal" class="modal fade show" tabindex="-1" style="display: block; background: rgba(0, 0, 0, .5);">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content" data-aos="zoom-in" data-aos-duration="300">
                    <div class="modal-header">
                        <h5 class="modal-title">Enrégistrement de produits</h5>
                        <button type="button" class="btn-close" @click="EditModal = false" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        


                        <div class="image mb-3" v-if="getProd.image == null">

                            <label for="img">
                                <input type="file" id="img" hidden @change="handleFileImgUpdate">
                                <i class="fa fa-cloud-upload-alt"></i>
                                <p>Télécharger une image</p>
                            </label>

                            <section class="progress-area" v-if="ShowProgress">
                                <li class="rows">

                                    <i class="fas fa-file-alt" ></i>
                                    <div class="content">
                                        <div class="details">
                                            <span class="name">{{ names }} ~ Téléchargement...</span>
                                            <div class="percent">{{ percent }}%</div>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress" :style="'width:'+ percent + '%'"></div>
                                        </div>
                                    </div>

                                </li>
                            </section>
                            <section class="uploaded-area" v-if="ShowUploded">
                                <li class="rows">
                                    <div class="content">
                                        <i class="fas fa-file-alt" ></i>
                                        <div class="details">
                                            <span class="name">{{names}} ~ Télécharger</span>
                                            <div class="size">{{size}}</div>
                                        </div>
                                    </div>
                                    <i class="fas fa-check"></i>
                                </li>
                            </section>

                        </div>
                        <div class="imageEdit" v-if="getProd.image != null">
                            <div class="image">
                                <img :src="getProd.image" alt="">
                            </div>
                            <button type="button" class="btn btn-light " @click="DelImage"> <i class="fas fa-trash-can"></i> </button>
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Nom du produits :</label>
                            <input type="text" class="form-control" v-model="getProd.name">
                        </div>
                        <div class="rows_cat">
                            <div class="form-group mb-3 cat">
                                <label for="">Catégorie :</label>
                                <select class="form-select" v-model="getProd.categorie_id">
                                    <option selected disabled>Selectionnez une catégorie</option>
                                    <option v-for="cat in allCat" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                            </div>
                            <button class="btn btn-primary mt-2" @click="cat"> <i class="fa fa-plus" aria-hidden="true"></i> </button>
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Quantité :</label>
                            <input type="number" class="form-control" v-model="getProd.qte">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Prix Achat :</label>
                            <input type="number" class="form-control" v-model="getProd.Pachats">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Prix Vente (Unité) :</label>
                            <input type="number" class="form-control" v-model="getProd.Pvente">
                        </div>



                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="EditModal = false" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" @click="UpdateProduit">Modifier</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin Modal Edit -->



    </div>

    <i @click="addModal = true" class="fa fa-plus bas"></i>




  </div>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
export default {
    data(){
        return{
            addModal: false,
            menu: false,
            addcat: false,
            ViewModal: false,
            EditModal: false,
            cats:{
                name: ""
            },
            allCat:{},
            data:{
                image:"",
                name:"",
                categorie_id:"",
                qte:"",
                Pachats:"",
                Pvente:""
            },
            percent: "",
            names:"",
            ShowProgress: false,
            ShowUploded:false,
            size:"",
            allProd:{},
            currentPage: 1,
            totalPage: 6,
            getProd: {}
        }
    },
    methods:{
        cat(){
            this.addcat = !this.addcat
            this.addModal = !this.addModal
        },
        async AddCategorie(){
            const res = await axios.post('/create_Catégorie', this.cats)
            if (res.status === 200) {

                this.allCat.unshift(res.data.categorie)
                
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    title: "Catégorie enrégistrer avec succès",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: "success"
                })
                this.addcat = !this.addcat
                this.addModal = !this.addModal
                this.cats.name = ""
                
            }
        },
        async AllCategorie(){
            const res = await axios.get('/all_Catégorie')
            if (res.status === 200) {
                this.allCat = res.data.allCat
            }
        },
        async handleFileImg(event){
            const selectImg = event.target.files[0]
            if (selectImg && selectImg.type.startsWith('image/')) {
                const formData = new FormData()
                formData.append('image',selectImg)

                try {
                    
                    const res = await axios.post('/uploadimg',formData,{
                        onUploadProgress:(ProgressEvent)=>{
                            const percentCompleted = Math.round((ProgressEvent.loaded * 100) / ProgressEvent.total)
                            this.percent = percentCompleted
                            this.names = selectImg.name

                            if (this.percent < 100) {
                                this.ShowProgress = true
                            }else if(this.percent && selectImg.name){
                                this.ShowProgress = false
                                this.ShowUploded = true
                            }

                            if (selectImg.size < 1000) {
                                this.size = selectImg.size + " o";
                            } else if (selectImg.size >= 1000 && selectImg.size < 1000000) {
                                this.size = (selectImg.size / 1000).toFixed(2) + " ko";
                            } else if (selectImg.size >= 1000000) {
                                this.size = (selectImg.size / 1000000).toFixed(2) + " Mo";
                            }
                        }
                    })

                    if (res.status === 200) {
                        this.data.image = res.data.image_url
                    }

                } catch (error) {
                    
                }

            }
        },
        async handleFileImgUpdate(event){
            const selectImg = event.target.files[0]
            if (selectImg && selectImg.type.startsWith('image/')) {
                const formData = new FormData()
                formData.append('image',selectImg)

                try {
                    
                    const res = await axios.post('/uploadimg',formData,{
                        onUploadProgress:(ProgressEvent)=>{
                            const percentCompleted = Math.round((ProgressEvent.loaded * 100) / ProgressEvent.total)
                            this.percent = percentCompleted
                            this.names = selectImg.name

                            if (this.percent < 100) {
                                this.ShowProgress = true
                            }else if(this.percent && selectImg.name){
                                this.ShowProgress = false
                                this.ShowUploded = true
                            }

                            if (selectImg.size < 1000) {
                                this.size = selectImg.size + " o";
                            } else if (selectImg.size >= 1000 && selectImg.size < 1000000) {
                                this.size = (selectImg.size / 1000).toFixed(2) + " ko";
                            } else if (selectImg.size >= 1000000) {
                                this.size = (selectImg.size / 1000000).toFixed(2) + " Mo";
                            }
                        }
                    })

                    if (res.status === 200) {
                        this.getProd.image = res.data.image_url
                    }

                } catch (error) {
                    
                }

            }
        },
        async addProduits(){
            const res = await axios.post('/creat_Produits',this.data)
            if (res.status === 200) {

                Swal.fire({
                    position: "center",
                    icon:"success",
                    title: "Enrégistrement effèctuer avec succès",
                    showConfirmButton:false,
                    timer: 1500
                })
                this.allProd.unshift(res.data.produits)
                this.data.image = ""
                this.data.name = ""
                this.data.categorie_id = ""
                this.data.qte = ""
                this.data.Pachats = ""
                this.data.Pvente = ""
                this.ShowProgress = false
                this.ShowUploded = false
                this.addModal = false
            }
        },
        ModalFermer(){
            this.addModal = false
            this.ShowProgress = false
            this.ShowUploded = false
        },
        async allProduits(page){
            const res = await axios.get('/allProduits?page='+ page)
            if (res.status === 200) {
                this.allProd = res.data.produits.data
                this.currentPage = res.data.produits.current_page
                this.totalPage = res.data.produits.last_page
            }
        },
        async GetProduitView(id){
            this.ViewModal = true
            const res = await axios.get("/getProduit/" + id)
            if (res.status === 200) {
                this.getProd = res.data.produit
            }
        },
        async GetProduitEdit(id){
            this.EditModal = true
            const res = await axios.get("/getProduit/" + id)
            if (res.status === 200) {
                this.getProd = res.data.produit
            }
        },
        GetCategoryName(id){  
            const categorie = this.allCat.find(cat => cat.id === id)
            return categorie ? categorie.name : ""
            // return this.allCat[id] ? this.allCat[id].name : ''
        },
        async DelImage(){
            const res = await axios.post('/delete_image',{image: this.getProd.image})
            if (res.status === 200) {
                this.getProd.image = null
            }
        },
        async UpdateProduit(){
            try {

                if (!this.getProd.image || this.getProd.image.trim() === "" || !this.getProd.name || this.getProd.name.trim() === "") {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        title: "Veuillez remplir tous les champs",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: "error"
                    })
                    this.EditModal = true
                }else{
                    const res = await axios.post('/update_Produit', this.getProd)
                    if (res.status === 200) {
                        Swal.fire({
                            position: "center",
                            icon:"success",
                            title: "Modification effectuer",
                            showConfirmButton:false,
                            timer: 1500
                        })
                        
                        const index = this.allProd.findIndex(prod => prod.id === this.getProd.id)
                        if (index !== -1) {
                            this.allProd[index] = this.getProd
                        }
                        this.getProd ={}
                        this.EditModal = false
                    }
                }

            } catch (error) {
                console.log(error)
            }
        },
        async GetProdDel(id){
            const res = await axios.get("/getProduit/" + id)
            if (res.status === 200) {
                this.getProd = res.data.produit

                Swal.fire({
                    title:"Voulez-vous supprimer ce produits ?",
                    text:"Vous ne pourrez plus revenir en arrière !",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonColor: "#d33",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Supprimer",
                    cancelButtonText:"Fermer"
                }).then(async (result)=>{
                    if (result.isConfirmed) {
                        const ress = await axios.post('/delProd',this.getProd)
                        if (ress.status === 200) {
                            Swal.fire({
                                position:"center",
                                icon:"success",
                                title: "Suppression effectuer",
                                showConfirmButton: false,
                                timer: 1500
                            })

                            const index = this.allProd.findIndex(prod => prod.id === this.getProd.id)
                            if(index !== -1){
                                this.allProd.splice(index,1)
                            }
                        }
                    }
                })
            }
        }
      
    },
    created(){
        this.AllCategorie()
        this.allProduits()
    },
    
}
</script>

<style>

</style>