<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    /** @use HasFactory<\Database\Factories\RessourceFactory> */
    use HasFactory;
    protected $fillable =[
        'nom',
        'type',
        'localisation',
        'description',
        'capacite'
    ];

    //les relations
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
