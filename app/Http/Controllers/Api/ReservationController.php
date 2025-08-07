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
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        return $reservation ;
    }

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
