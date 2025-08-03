<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    protected $fillable=[
        'nom',
        'description'
    ];

    //les relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
