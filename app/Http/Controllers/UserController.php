<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function inscription(Request $request){

        $utiliateurData = $request->validate([
            "name" => ["string","required"],
            "email"=> ["required","email","unique:users,email"],
            "password"=> ["required","string","min:8","max:30"]
        ]);

        $utilisateurs = User::create([
            "name"=> $utiliateurData["name"],
            "email"=> $utiliateurData['email'],
            "password"=> bcrypt($utiliateurData['password'])
        ]);

        return response()->json([
            'inscription' => $utilisateurs
        ]);

    }

    public function connexion(Request $request){

        $utiliateurData = $request->validate([
            "email"=> ["required","email"],
            "password"=> ["required","string","min:3","max:30"]
        ]);

        $user = User::Where("email", $utiliateurData["email"])->first();
        
        if (Auth::attempt($utiliateurData)) { // Nouvelle manière de vérification des champs

            $token = $user->createToken("CLE_SECRETE")->plainTextToken;
            return response()->json([
                "User" => $user,
                "Token" => $token      
            ],200);

        }else{
            return response()->json([
                "message"=> "Adresse email ou mot de passe incorrect"
            ],401);
        }

        /* $user = User::Where("email", $utiliateurData["email"])->first();
        
         if ($user) {
             if (Hash::check($utiliateurData["password"],$user->password)) {

                 $token = $user->createToken("CLE_SECRETE")->plainTextToken;
                
                 return response()->json([
                     "User" => $user,
                     "Token" => $token
                 ],200);

             }else{
                 return response()->json([
                     "message"=> "Mots de passe inccorect"
                 ],401);
             }
         }else{
             return response()->json([
                 "message"=> "L'adresse email n'est pas valide"
             ],401);
         }*/
        



    }

    public function enLigne(){
        $user = Auth::user();
        
        if ($user) {
            return response()->json([
                'user' => $user
            ]);
        }
    }

    public function deconnexion(Request $request){
        $user = auth()->user();
        
        if ($user) {
            $user->tokens->each(function($token){
                $token->delete();
            });
        }

        return response()->json([
            "message"=>"Deconnexion"
        ],200);
    }
}
