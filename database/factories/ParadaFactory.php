<?php

namespace Database\Factories;

use App\Models\Parada;
use App\Models\Recorrido;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Parada>
 */
class ParadaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recorrido_id' => Recorrido::factory(),
            'orden' => $this->faker->numberBetween(1, 10),
            'nombre' => 'Parada '.$this->faker->streetName(),
            'referencia' => $this->faker->optional()->sentence(3),
            'latitud' => $this->faker->randomFloat(7, -16.55, -16.45),
            'longitud' => $this->faker->randomFloat(7, -68.20, -68.10),
        ];
    }
}
