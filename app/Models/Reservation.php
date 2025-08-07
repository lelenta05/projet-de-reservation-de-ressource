<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    protected $fillable=[
        'user_id',
        'ressource_id',
        'date_debut',
        'date_fin',
        'statut'
    ];

    //les relations
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function ressource()
    {
        return $this->belongsTo(Ressource::class,'ressource_id');
    }
}

