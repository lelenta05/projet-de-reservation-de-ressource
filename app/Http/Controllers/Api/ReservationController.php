<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
use App\Notifications\ReservationCreated;
use App\Notifications\ReservationStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');//pour applique l'auth sur les routes de reservations 
        $this->authorizeResource(Reservation::class,'reservation');//proteger les ressource crud de reservation
    }
/**
 * @OA\Get(
 *     path="/reservations",
 *     summary="Lister tous les reservations ",
 *     tags={"Reservations"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Liste des reservations . L'admin de la plateforme peut voir tous les reservations tandis qu' un utilisateur avec le role user ne peut voir uniquement que ses propres reservations ",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Reservation"))
 *     )
 * )
 */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user= Auth::user();//recuper l'user connecte 
        //verification 
        if($user->role && $user->role->nom === 'admin'){
            return Reservation::all();//affiche tous les reservations 
        }
        return Reservation::where('user_id',$user->id)->get();//recupere les reservation de l'user et affiche les 
    }
/**
 * @OA\Post(
 *     path="/reservations",
 *     summary="Créer une nouvelle reservation ",
 *     tags={"Reservations"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *     required={ "ressource_id","date_debut","date_fin","statut"},
 *     @OA\Property(property="ressource_id", type="integer", example=2),
 *     @OA\Property(property="date_debut", type="string", format="date-time", example="2025-02-01T10:00:00"),
 *     @OA\Property(property="date_fin", type="string", format="date-time", example="2025-02-02T15:30:00"), 
 *     @OA\Property(property="statut",type="string",example="pending" )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Reservation valide pour une ressource avec notification par email a l'utilisateur qui a effectue la reservation ",
 *         @OA\JsonContent(ref="#/components/schemas/Resservation")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erreur de validation  "
 *     )
 * )
 */
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request)
    {
        $data= $request->validated();
        $data['user_id'] = Auth::id();//l'id de l'user connecte 
        $reservation = Reservation::create($data);
        $reservation->user->notify(new ReservationCreated($reservation));//notification par mail suite a la reservation d'une ressource 
        return response()->json($reservation,201);
    }
/**
 * @OA\Get(
 *     path="/reservations/{reservation}",
 *     summary="Voir une reservation ",
 *     tags={"Reservations"},
 *     security={{"sanctum":{}}},
*       @OA\Parameter(
*         name="reservation",
*         in="path",
*         required=true,
*         description="ID de la réservation",
*         @OA\Schema(type="integer")
*     ),

 *     @OA\Response(
 *         response=200,
 *         description="Affichage de la reservation qui elle appartient a l'utilisateur qui fait la requete ou si c'est un administrateur ",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Reservation"))
 *     ),
 *   @OA\Response(
 *         response=404,
 *         description="Erreur pour indique a l'utilisateur qui n'a pas le droit de voir cette reservation "
 *     )
 * )
 */
    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        return $reservation ;
    }
/**
 * @OA\PUT(
 *     path="/reservations/{reservation}",
 *     summary="Modifier une reservation existante ",
 *     tags={"Reservations"},
 *     security={{"sanctum":{}}},
*        @OA\Parameter(
*         name="reservation",
*         in="path",
*         required=true,
*         description="ID de la réservation à modifier",
*         @OA\Schema(type="integer")
*     ),
*     @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*             required={"date_debut","date_fin","statut"},
*             @OA\Property(property="date_debut", type="string", format="date-time", example="2025-02-01T10:00:00"),
*             @OA\Property(property="date_fin", type="string", format="date-time", example="2025-02-02T15:30:00"),
*             @OA\Property(property="statut", type="string", example="approved")
*             
*         )
*     ),
*     @OA\Response(
*         response=201,
*         description="Reservation mise a jour avec notification par email a l'utilisateur si le statut a ete modifie  ",
*         @OA\JsonContent(ref="#/components/schemas/Resservation")
*     ),
*     @OA\Response(
*         response=422 ,
*         description="Erreur de validation  "
*     )
* )
*/
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $oldStatut = $reservation->statut ;//recupere le statut de la reservation 
        $reservation->update($request->validated());
        //si le statut est change , notifie l'user 
        if($oldStatut !== $reservation->statut){
            $reservation->user->notify(new ReservationStatusUpdated($reservation));
        }
        return response()->json($reservation);
    }
/**
 * @OA\DELETE(
 *     path="/reservations/{reservation}",
 *     summary="Supprime une reservation ",
 *     tags={"Reservations"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
*         name="reservation",
*         in="path",
*         required=true,
*         description="ID de la réservation à supprimer",
*         @OA\Schema(type="integer")
*     ),
*     @OA\Response(
*         response=204,
*         description="Reservation supprime  ",
*         @OA\JsonContent(ref="#/components/schemas/Reservation")
*     ),
*     @OA\Response(
*         response=404,
*         description="Erreur si l'utilisation n'est celui qui a fait la reservation "
*     )
* )
*/
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return response()->json([
            'message'=> 'Reservation supprimée',
        ]);
    }
}
