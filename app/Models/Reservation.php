<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//les commentaires de L5-Swagger 
/**
 * @OA\Schema(
 *     schema="Reservation",
 *     required={"user_id", "ressource_id", "date_debut","date_fin","statut"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="ressource_id", type="integer", example=2),
 *     @OA\Property(property="date_debut", type="string", format="date-time", example="2025-02-01T10:00:00"),
 *     @OA\Property(property="date_fin", type="string", format="date-time", example="2025-02-02T15:30:00"), 
 *     @OA\Property(
 *         property="statut",
 *         type="string",
 *         enum={"pending", "approved", "rejected"},
 *         example="pending"
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
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

