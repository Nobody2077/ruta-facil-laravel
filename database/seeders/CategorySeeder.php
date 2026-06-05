<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Transporte urbano',
                'slug' => 'transporte-urbano',
                'color' => '#2563eb',
                'description' => 'Rutas de minibuses, micros y trufis dentro de la ciudad de El Alto.',
            ],
            [
                'name' => 'Transporte rural',
                'slug' => 'transporte-rural',
                'color' => '#16a34a',
                'description' => 'Conexiones hacia comunidades y zonas rurales cercanas.',
            ],
            [
                'name' => 'Conexion intermunicipal',
                'slug' => 'conexion-intermunicipal',
                'color' => '#dc2626',
                'description' => 'Rutas que conectan El Alto con La Paz y otros municipios.',
            ],
            [
                'name' => 'Ruta universitaria',
                'slug' => 'ruta-universitaria',
                'color' => '#7c3aed',
                'description' => 'Recorridos frecuentes para estudiantes de institutos y universidades.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category,
            );
        }
    }
}
