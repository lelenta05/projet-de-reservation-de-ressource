<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//les commentaires de L5-Swagger 
/**
 * @OA\Schema(
 *     schema="Role",
 *     required={"nom", "description"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nom", type="string", example="admin"),
 *     @OA\Property(property="description", type="string", example="Il a tous les droits dans la plateforme "),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 *  )
 */
class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    protected $fillable=[
        'nom',
        'description'
    ];

    //les relations
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
