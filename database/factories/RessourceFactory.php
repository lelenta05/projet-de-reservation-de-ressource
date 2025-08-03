<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ressource>
 */
class RessourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      
        return [
            'nom' => $this->faker->word ,
            'type' => $this->faker->randomElement(['Salle  de réunion','Poste de travail','VideoPorjecteurs']),
            'localisation' => $this->faker->address,
            'description' => $this->faker->optional()->sentence,
            'capacite' => $this->faker->numberBetween(1, 100),
        ];
    }
}
