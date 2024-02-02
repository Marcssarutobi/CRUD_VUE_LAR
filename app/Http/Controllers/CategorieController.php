<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function addCategorie(Request $request){

        $this->validate($request,[
            'name'=>'required'
        ]);

        $catégorie = Categorie::create([
            'name'=>$request->name
        ]);

        return response()->json([
            'categorie'=>$catégorie
        ]);

    }

    public function allCategorie(){
        $categorie = Categorie::orderBy('id','desc')->get();
        return response()->json([
            'allCat'=> $categorie
        ]);
    }
}
