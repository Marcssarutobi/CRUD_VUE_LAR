<?php

namespace App\Http\Controllers;

use App\Models\Ventes;
use Illuminate\Http\Request;

class VentesController extends Controller
{
    public function addvente(Request $request){
        $this->validate($request,[
            'ProductId'=>'required',
            'QteAchat'=>'required|numeric',
            'PTotal'=>'required|numeric'
        ]);
        $vente = Ventes::create([
            'ProductId'=>$request->ProductId,
            'QteAchat'=>$request->QteAchat,
            'PTotal'=>$request->PTotal
        ]);

        return response()->json([
            'ventes'=>$vente
        ]);
    }
}
