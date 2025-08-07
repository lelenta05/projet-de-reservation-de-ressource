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
     * Display a listing of the resource.(get)
     */
    public function index()
    {
        return Ressource::all();
    }

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
     * Display the specified resource.(get)
     */
    public function show(Ressource $ressource)
    {
        return $ressource ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRessourceRequest $request, Ressource $ressource)
    {
        $ressource->update($request->validated());
        return response()->json($ressource);
    }

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
