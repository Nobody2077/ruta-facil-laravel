<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $origin = $this->faker->randomElement(['Ceja', 'Rio Seco', 'Senkata', 'Villa Adela', '16 de Julio', 'Satelite']);
        $destination = $this->faker->randomElement(['UPEA', 'Villa Dolores', 'Ciudad Satelite', 'Tilata', 'Achocalla', 'Ventilla']);
        $title = 'Ruta '.$origin.' - '.$destination.' '.Str::random(6);

        return [
            'category_id' => Category::factory(),
            'created_by' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'origin' => $origin,
            'destination' => $destination,
            'description' => $this->faker->paragraph(3),
            'status' => $this->faker->randomElement(['planificado', 'activo', 'pausado', 'archivado']),
            'estimated_minutes' => $this->faker->numberBetween(15, 95),
            'distance_km' => $this->faker->randomFloat(2, 2, 35),
        ];
    }
}
