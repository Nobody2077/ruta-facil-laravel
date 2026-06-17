<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Recorrido;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecorridoSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $categoryMap = Category::all()->keyBy('slug');
        $admin = User::where('email', 'admin@rutafacil.test')->first();

        // [categorySlug, codigo, nombre, origen, destino, tarifa, [paradas...]]
        $recorridos = [
            ['transporte-urbano', 'L-101', 'Linea Ceja - Rio Seco', 'Ceja', 'Rio Seco', 2.50, [
                'Ceja El Alto', 'Av. Juan Pablo II', 'Plaza La Paz', '16 de Julio', 'Rio Seco',
            ]],
            ['transporte-urbano', 'L-102', 'Linea Villa Adela - UPEA', 'Villa Adela', 'UPEA', 3.00, [
                'Villa Adela', 'Ballivian', 'Av. Bolivia', 'UPEA',
            ]],
            ['transporte-rural', 'L-201', 'Linea Senkata - Ventilla', 'Senkata', 'Ventilla', 4.00, [
                'Senkata', 'Distrito 8', 'Ventilla',
            ]],
            ['conexion-intermunicipal', 'L-301', 'Linea El Alto - La Paz Centro', 'Ceja', 'La Paz Centro', 3.50, [
                'Ceja El Alto', 'Autopista', 'Plaza del Estudiante', 'La Paz Centro',
            ]],
            ['ruta-universitaria', 'L-401', 'Linea UPEA - Villa Dolores', 'UPEA', 'Villa Dolores', 2.00, [
                'UPEA', 'Av. Sucre A', 'Villa Dolores',
            ]],
        ];

        foreach ($recorridos as [$categorySlug, $codigo, $nombre, $origen, $destino, $tarifa, $paradas]) {
            $recorrido = Recorrido::updateOrCreate(
                ['codigo' => $codigo],
                [
                    'category_id' => $categoryMap[$categorySlug]->id ?? null,
                    'created_by' => $admin?->id,
                    'nombre' => $nombre,
                    'origen' => $origen,
                    'destino' => $destino,
                    'descripcion' => 'Recorrido de transporte publico entre '.$origen.' y '.$destino.'.',
                    'tarifa_bs' => $tarifa,
                    'activo' => true,
                ],
            );

            $recorrido->paradas()->delete();
            foreach (array_values($paradas) as $index => $nombreParada) {
                $recorrido->paradas()->create([
                    'orden' => $index + 1,
                    'nombre' => $nombreParada,
                    'referencia' => 'Parada sobre el recorrido '.$codigo,
                ]);
            }
        }
    }
}
