<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
/**
 * @OA\Post(
 *     path="/register",
 *     summary="Créer un nouveau utilisateur",
 *     tags={"Users"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"nom", "email","password","role_id"},
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Utilisateur créé ainsi que son token ",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erreur de validation"
 *     )
 * )
 */
    //Inscription
    public function register(Request $request)
    {
        //validation des donnees 
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);
        //creation 
        $user=User::create([
            'nom' => $request->nom,
            'email'=> $request->email,
            'password'=> bcrypt($request->password),
            'role_id' => $request->role_id
        ]);
        //attribution du token 
        $token = $user->createToken('api-token')->plainTextToken ;
        //retour avec un code 201 pour dire que l'user a ete bien creer 
        return response()->json([
            'user'=> $user,
            'token'=> $token
        ],201);

    }
/**
 * @OA\Post(
 *     path="/login",
 *     summary="Connexion d'un utilisateur",
 *     tags={"Users"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={ "email","password"},
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Utilisateur connecté avec son token ",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Identifiant incorrecte"
 *     )
 * )
 */
    //connexion
    public function login(Request $request)
    {
        //validation 
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        //verification de l'email dans la db 
        $user= User::where('email',$request->email)->first();
        //verification du mot de passe 
        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json(
                ['message' => 'Identifiants invalides',401],//le code 401 pour les informations invalides 

            );
        }
        $token=$user->createToken('api-token')->plainTextToken ;

        return response()->json([
            'user' => $user,
            'token'=> $token
        ]);

    }
/**
 * @OA\Post(
 *     path="/logout",
 *     summary="Deconnexion ",
 *     tags={"Users"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=201,
 *         description="Deconnecte ainsi que la suppression de tous les tokens de l'utilisateur concerne . ",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 * )
 */
    //Deconnexion
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();//suppimes tous les tokens de l'user qui se deconnecte 
        return response()->json([
            'message' => 'Deconnecte',
        ]);
    }
}
