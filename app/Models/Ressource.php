<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

//les commentaires de L5-Swagger 
/**
 * @OA\Schema(
 *     schema="Ressource",
 *     required={"nom", "type", "localisation","description","capacite"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nom", type="string", example="Salle de Formation"),
 *     @OA\Property(property="type", type="string", example="Salle"),
 *     @OA\Property(property="localisation", type="string", example="Batiment"),
 *     @OA\Property(property="description", type="string", example= "C'est une salle de formation "), 
 *     @OA\Property(property="capacite", type="integer", example=30 ), 
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 *  )
 */
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
