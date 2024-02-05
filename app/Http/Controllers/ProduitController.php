<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{

    public function uploadImg(Request $request){ # Uploder une image dans un dossier

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:20048'
        ]);

        if ($request->hasFile('image')) {

            // Génération d'un nom unique pour l'image
            $picName = time() . '.' . $request->file('image')->extension();
    
            // Déplacement du fichier vers le répertoire public/images
            $request->file('image')->move(public_path('images'), $picName);
    
            // Retourner l'URL de l'image téléchargée
            return response()->json(['image_url' => "/images/$picName"]);
    
        } else {
            // Aucun fichier image n'a été téléchargé, retourner une erreur
            return response()->json(['error' => "Aucune image n'a été téléchargée."], 400);
        }

    }

    public function addProduits(Request $request){
        $this->validate($request,[
            "image" => "required",
            "name" => "required",
            "categorie_id" => "required",
            "qte" => "required",
            "Pachats" => "required",
            "Pvente" => "required",
        ]);

        $produit = Produit::create([
            "image"=>$request->image,
            "name"=>$request->name,
            "categorie_id"=>$request->categorie_id,
            "qte"=>$request->qte,
            "Pachats"=>$request->Pachats,
            "Pvente"=>$request->Pvente,
        ]);

        return response()->json([
            "produits"=>$produit
        ]);
    }

    public function allProduits(){

        $produit = Produit::orderBy('id',"desc")->paginate(6);
        return response()->json([
            "produits"=>$produit
        ]); 

    }
    public function Produits(){

        $produit = Produit::orderBy('id',"desc")->get();
        return response()->json([
            "produits"=>$produit
        ]); 

    }

    public function show($id){
        $produit = Produit::where('id',$id)->first();
        return response()->json([
            'produit' => $produit
        ]);
    }

    public function deleteImage(Request $request, $hasFullPath = false){

        $request->validate([
            'image'=>'required'
        ]);

        if (!$hasFullPath) {
            $filePath = public_path() .$request->image;
        }

        if(file_exists($filePath)){
            @unlink($filePath);
        }

        return 'done';

    }

    public function updateProduits(Request $request){

        $request->validate([
            "image" => "required",
            "name" => "required",
            "categorie_id" => "required",
            "qte" => "required",
            "Pachats" => "required",
            "Pvente" => "required",
        ]);

        $produit = Produit::where('id',$request->id)->update([
            "image"=>$request->image,
            "name"=>$request->name,
            "categorie_id"=>$request->categorie_id,
            "qte"=>$request->qte,
            "Pachats"=>$request->Pachats,
            "Pvente"=>$request->Pvente,
        ]);

        return response()->json([
            "produit"=>$produit
        ]);

    }

    public function delProd(Request $request, $hasFullPath = false){
        //Premièrement effacer le fichier originel du server
        if(!$hasFullPath){
            $filePath = public_path() . $request->image;
        }

        if(file_exists($filePath)){
            @unlink($filePath);
        }

        $produit = Produit::where('id',$request->id)->delete();

        return response()->json([
            "produit"=> $produit
        ]);
    }

    public function search($str){
        if (!empty($str)) {
            $produit = Produit::where('name','LIKE',"%{$str}%")->orderBy('id','asc')->get();
            return response()->json([
                'produit'=>$produit
            ]);
        }
    }


}
