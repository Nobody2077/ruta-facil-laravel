<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Recorrido;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Recorrido>
 */
class RecorridoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $origen = $this->faker->randomElement(['Ceja', 'Rio Seco', 'Senkata', 'Villa Adela', '16 de Julio', 'Satelite']);
        $destino = $this->faker->randomElement(['UPEA', 'Villa Dolores', 'Ciudad Satelite', 'Tilata', 'Achocalla', 'Ventilla']);

        return [
            'category_id' => Category::factory(),
            'created_by' => User::factory(),
            'nombre' => 'Recorrido '.$origen.' - '.$destino,
            'codigo' => 'R-'.strtoupper(Str::random(6)),
            'origen' => $origen,
            'destino' => $destino,
            'descripcion' => $this->faker->paragraph(2),
            'tarifa_bs' => $this->faker->randomFloat(2, 1.5, 5),
            'activo' => $this->faker->boolean(85),
        ];
    }
}
