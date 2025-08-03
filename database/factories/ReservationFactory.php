<?php

namespace Database\Factories;

use App\Models\Ressource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date_debut= $this->faker->dateTimeBetween('now', '+1 week');
        $date_fin = (clone $date_debut)->modify('+'.rand(1, 4).' hours');
        return [
            'user_id' => User::factory(),
            'ressource_id' => Ressource::factory(),
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'statut' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
