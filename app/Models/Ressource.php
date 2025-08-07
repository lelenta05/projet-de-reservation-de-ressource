<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Ressource extends Model
{
    /** @use HasFactory<\Database\Factories\RessourceFactory> */
    use HasFactory,HasApiTokens;
    protected $fillable =[
        'nom',
        'type',
        'localisation',
        'description',
        'capacite'
    ];

    //les relations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
