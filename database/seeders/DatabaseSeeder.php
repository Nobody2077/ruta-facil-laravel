<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Opinion;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
        ]);

        // El seeder de recorridos se ejecuta al final (depende de categorias y del admin).

        $roles = Role::all()->keyBy('slug');
        $categories = Category::all();

        $allUsers = collect([
            ['name' => 'Admin Ruta Facil', 'email' => 'admin@rutafacil.test'],
            ['name' => 'Moderador Ruta Facil', 'email' => 'moderador@rutafacil.test'],
            ['name' => 'Lucia Choque', 'email' => 'lucia.choque@rutafacil.test'],
            ['name' => 'Marco Quispe', 'email' => 'marco.quispe@rutafacil.test'],
            ['name' => 'Ana Mamani', 'email' => 'ana.mamani@rutafacil.test'],
            ['name' => 'Diego Flores', 'email' => 'diego.flores@rutafacil.test'],
            ['name' => 'Roxana Condori', 'email' => 'roxana.condori@rutafacil.test'],
            ['name' => 'Jorge Copa', 'email' => 'jorge.copa@rutafacil.test'],
            ['name' => 'Carla Apaza', 'email' => 'carla.apaza@rutafacil.test'],
            ['name' => 'Nestor Callisaya', 'email' => 'nestor.callisaya@rutafacil.test'],
        ])->map(fn (array $user): User => User::updateOrCreate(
            ['email' => $user['email']],
            [
                'name' => $user['name'],
                'password' => Hash::make('password'),
            ],
        ));

        $admin = $allUsers->firstWhere('email', 'admin@rutafacil.test');
        $moderator = $allUsers->firstWhere('email', 'moderador@rutafacil.test');
        $users = $allUsers->whereNotIn('email', ['admin@rutafacil.test', 'moderador@rutafacil.test']);

        $admin->roles()->syncWithoutDetaching([
            $roles['admin']->id => ['assigned_at' => now()],
        ]);
        $moderator->roles()->syncWithoutDetaching([
            $roles['moderador']->id => ['assigned_at' => now()],
        ]);

        $users->each(function (User $user) use ($roles): void {
            $user->roles()->syncWithoutDetaching([
                $roles['usuario']->id => ['assigned_at' => now()],
            ]);
        });

        $categoryMap = $categories->keyBy('slug');
        $projectRows = [
            ['transporte-urbano', 'Ruta Ceja - Rio Seco', 'ruta-ceja-rio-seco', 'Ceja', 'Rio Seco', 'activo', 35, 9.50],
            ['transporte-urbano', 'Ruta Villa Adela - UPEA', 'ruta-villa-adela-upea', 'Villa Adela', 'UPEA', 'activo', 42, 12.20],
            ['transporte-urbano', 'Ruta 16 de Julio - Ciudad Satelite', 'ruta-16-julio-ciudad-satelite', '16 de Julio', 'Ciudad Satelite', 'planificado', 28, 7.80],
            ['transporte-urbano', 'Ruta Senkata - Ventilla', 'ruta-senkata-ventilla', 'Senkata', 'Ventilla', 'pausado', 55, 16.40],
            ['transporte-rural', 'Ruta Rio Seco - Tilata', 'ruta-rio-seco-tilata', 'Rio Seco', 'Tilata', 'activo', 63, 22.10],
            ['transporte-rural', 'Ruta Senkata - Achocalla', 'ruta-senkata-achocalla', 'Senkata', 'Achocalla', 'planificado', 72, 28.30],
            ['conexion-intermunicipal', 'Ruta El Alto - La Paz Centro', 'ruta-el-alto-la-paz-centro', 'Ceja', 'La Paz Centro', 'activo', 45, 10.60],
            ['conexion-intermunicipal', 'Ruta Rio Seco - Viacha', 'ruta-rio-seco-viacha', 'Rio Seco', 'Viacha', 'activo', 68, 24.70],
            ['ruta-universitaria', 'Ruta UPEA - Villa Dolores', 'ruta-upea-villa-dolores', 'UPEA', 'Villa Dolores', 'activo', 31, 8.30],
            ['ruta-universitaria', 'Ruta Tecnologico - Ceja', 'ruta-tecnologico-ceja', 'Tecnologico', 'Ceja', 'planificado', 25, 6.20],
            ['transporte-urbano', 'Ruta Villa Exaltacion - Plaza Ballivian', 'ruta-villa-exaltacion-plaza-ballivian', 'Villa Exaltacion', 'Plaza Ballivian', 'activo', 38, 11.30],
            ['conexion-intermunicipal', 'Ruta El Alto - Mallasa', 'ruta-el-alto-mallasa', 'Ceja', 'Mallasa', 'archivado', 80, 31.80],
        ];

        $projects = collect($projectRows)->map(function (array $row, int $index) use ($categoryMap, $allUsers): Project {
            [$categorySlug, $title, $slug, $origin, $destination, $status, $minutes, $distance] = $row;

            return Project::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $categoryMap[$categorySlug]->id,
                    'created_by' => $allUsers->values()->get($index % $allUsers->count())->id,
                    'title' => $title,
                    'origin' => $origin,
                    'destination' => $destination,
                    'description' => 'Proyecto de digitalizacion y seguimiento para la ruta '.$origin.' - '.$destination.'.',
                    'status' => $status,
                    'estimated_minutes' => $minutes,
                    'distance_km' => $distance,
                ],
            );
        });

        $opinions = collect();

        foreach (range(1, 50) as $index) {
            $project = $projects->values()->get(($index - 1) % $projects->count());
            $user = $allUsers->values()->get(($index - 1) % $allUsers->count());

            $message = 'Reporte '.$index.': se registra informacion ciudadana para mejorar tiempos, paradas y claridad del recorrido.';

            $opinions->push(Opinion::updateOrCreate(
                ['message' => $message],
                [
                    'user_id' => $user->id,
                    'project_id' => $project->id,
                    'name' => $user->name,
                    'route' => $project->origin.' - '.$project->destination,
                    'status' => ['nuevo', 'revisado', 'archivado'][($index - 1) % 3],
                ],
            ));
        }

        $projects->values()->each(function (Project $project, int $index) use ($allUsers): void {
            Comment::updateOrCreate(
                [
                    'commentable_type' => Project::class,
                    'commentable_id' => $project->id,
                    'body' => 'Comentario de seguimiento para validar paradas y puntos conflictivos de esta ruta.',
                ],
                [
                    'user_id' => $allUsers->values()->get($index % $allUsers->count())->id,
                    'status' => 'visible',
                ],
            );
        });

        $opinions->take(15)->values()->each(function (Opinion $opinion, int $index) use ($allUsers): void {
            Comment::updateOrCreate(
                [
                    'commentable_type' => Opinion::class,
                    'commentable_id' => $opinion->id,
                    'body' => 'Respuesta de moderacion para clasificar el reporte y revisar su prioridad.',
                ],
                [
                    'user_id' => $allUsers->values()->get($index % $allUsers->count())->id,
                    'status' => 'visible',
                ],
            );
        });

        // Recorridos (cabecera) con sus paradas (detalle) — requiere categorias y admin ya creados.
        $this->call(RecorridoSeeder::class);
    }
}
