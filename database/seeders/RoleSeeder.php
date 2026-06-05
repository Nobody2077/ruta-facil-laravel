<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrador',
                'slug' => 'admin',
                'description' => 'Gestiona usuarios, rutas y registros sensibles del sistema.',
            ],
            [
                'name' => 'Moderador',
                'slug' => 'moderador',
                'description' => 'Revisa opiniones, reportes y comentarios enviados por usuarios.',
            ],
            [
                'name' => 'Usuario',
                'slug' => 'usuario',
                'description' => 'Consulta rutas y registra opiniones sobre el transporte publico.',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role,
            );
        }
    }
}
