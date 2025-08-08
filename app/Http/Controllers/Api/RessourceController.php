<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRessourceRequest;
use App\Http\Requests\UpdateRessourceRequest;
use App\Models\Ressource;
use Illuminate\Http\Request;

class RessourceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');//proteger les routes 
       $this->authorizeResource(Ressource::class, 'ressource');//applique la plocy automatiquement et authorizeResource avec un seul s car laravel comprend que l'anglais 
    }
/**
 * @OA\Get(
 *     path="/ressources",
 *     summary="Lister tous les ressources ",
 *     tags={"Ressources"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Liste des ressources .",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Ressource"))
 *     )
 * )
 */
    /**
     * Display a listing of the resource.(get)
     */
    public function index()
    {
        return Ressource::all();
    }
/**
 * @OA\Post(
 *     path="/ressources",
 *     summary="Créer une nouvelle ressource ",
 *     tags={"Ressources"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"nom", "type","localisation","description","capacite"},
* @OA\Property(property="nom", type="string", example="Salle de Formation"),
 *     @OA\Property(property="type", type="string", example="Salle"),
 *     @OA\Property(property="localisation", type="string", example="Batiment"),
 *     @OA\Property(property="description", type="string", example= "C'est une salle de formation "), 
 *     @OA\Property(property="capacite", type="integer", example=30 ), 
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Ressource cree avec succes mais seul l'admin peut le faire , les autres utilisateurs qui ont le role user n'ont pas ce droit ",
 *         @OA\JsonContent(ref="#/components/schemas/Ressource")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erreur de validation des donnees dans la requete  "
 *     )
 * )
 */

    /**
     * Store a newly created resource in storage.(post)
     */
    public function store(StoreRessourceRequest $request)
    {
        //creation en utilisant StoreRessourceRequest deja pre rempli 
        $ressource= Ressource::create($request->validated());
        return response()->json($ressource,201);//le code 201 pour le succes 
    }
/**
 * @OA\Get(
 *     path="/ressources/{ressource}",
 *     summary="Voir une ressource  ",
 *     tags={"Ressources"},
 *     security={{"sanctum":{}}},
*     @OA\Parameter(
*         name="ressource",
*         in="path",
*         required=true,
*         description="ID de la ressource",
*         @OA\Schema(type="integer")
*     ),
 *     @OA\Response(
 *         response=200,
 *         description="Affichage de la ressource avec son id ",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Ressource"))
 *     ),
 *      @OA\Response(
 *         response=404,
 *         description="Erreur ",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Ressource"))
 *     )
 * )
 */
    /**
     * Display the specified resource.(get)
     */
    public function show(Ressource $ressource)
    {
        return $ressource ;
    }
/**
 * @OA\PUT(
 *     path="/ressources/{ressource}",
 *     summary="Modifier une ressource existante ",
 *     tags={"Ressources"},
 *     security={{"sanctum":{}}},
*    @OA\Parameter(
*         name="ressource",
*         in="path",
*         required=true,
*         description="ID de la ressource à modifier",
*         @OA\Schema(type="integer")
*     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"nom","type","localisation","description","capacite"},
 *             
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Ressource mise a jour par l'admin uniquement  ",
 *         @OA\JsonContent(ref="#/components/schemas/Ressource")
 *     ),
 *     @OA\Response(
 *         response=404 ,
 *         description="Autorisation refuse pour cette action par l'utilisateur qui a le role user . Seul les admin peuvent modifier une ressource ."
 *     )
 * )
 */
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRessourceRequest $request, Ressource $ressource)
    {
        $ressource->update($request->validated());
        return response()->json($ressource);
    }
/**
 * @OA\DELETE(
 *     path="/ressources/{ressource}",
 *     summary="Supprime une reservation ",
 *     tags={"Ressources"},
 *     security={{"sanctum":{}}},
 * *     @OA\Parameter(
     *         name="ressource",
     *         in="path",
     *         required=true,
     *         description="ID de la ressource à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Ressource supprime  ",
 *         @OA\JsonContent(ref="#/components/schemas/Ressource")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Erreur si l'utilisation a le role user  "
 *     )
 * )
 */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ressource $ressource)
    {
        $ressource->delete();
        return response()->json(
            ['message' => 'Ressource supprime'],
        );
    }
}
