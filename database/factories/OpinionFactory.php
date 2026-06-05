<?php

namespace Database\Factories;

use App\Models\Opinion;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Opinion>
 */
class OpinionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'project_id' => Project::factory(),
            'name' => $this->faker->name(),
            'route' => $this->faker->randomElement([
                'Ceja - Rio Seco',
                'Rio Seco - Senkata',
                'Villa Adela - UPEA',
                '16 de Julio - Ciudad Satelite',
                'Senkata - Ventilla',
            ]),
            'message' => $this->faker->paragraph(2),
            'status' => $this->faker->randomElement(['nuevo', 'revisado', 'archivado']),
        ];
    }
}
