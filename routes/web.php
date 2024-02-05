<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProduitController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Catégorie
Route::post('/create_Catégorie',[CategorieController::class, 'addCategorie']);
Route::get('/all_Catégorie',[CategorieController::class, 'allCategorie']);



// Produits
Route::post('/uploadimg',[ProduitController::class, 'uploadImg']);
Route::post('/creat_Produits', [ProduitController::class, 'addProduits']);
Route::get('/allProduits',[ProduitController::class, 'allProduits']); //Touts les produits avec pagination
Route::get('/Produits',[ProduitController::class, 'Produits']); //Touts les produits sans pagination
Route::get('/getProduit/{id}',[ProduitController::class,'show']);
Route::post('/delete_image', [ProduitController::class, 'deleteImage']);
Route::post('/update_Produit', [ProduitController::class, 'updateProduits']);
Route::post('/delProd',[ProduitController::class, 'delProd']);
Route::get('/searchPrduits/{str}',[ProduitController::class, 'search']);
