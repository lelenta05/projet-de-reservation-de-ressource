<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole=Role::where('nom', 'admin')->first();
        $userRole = Role::where('nom','user')->first();
        //admin
        User::create([
            'nom' => 'Admin Principal',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
            'role_id' => $adminRole->id,
            'email_verified_at' => now(),
        ]);
        //user 1
         User::create([
            'nom' => 'Jean Dupont',
            'email' => 'jean.dupont@example.com',
            'password' => bcrypt('jean'),
            'role_id' => $userRole->id,
            'email_verified_at' => now(),
        ]);
        //user 2
         User::create([
            'nom' => 'Marie Martin',
            'email' => 'marie.martin@example.com',
            'password' => bcrypt('marie'),
            'role_id' => $userRole->id,
            'email_verified_at' => now(),
        ]);
    }
}
