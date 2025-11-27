<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <--- Importante

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Creacion de roles
        Role::factory()->createMany([
            ['name' => 'Administrador'],
            ['name' => 'Conductor'],
            ['name' => 'Cliente'],
        ]);

        // Creacion de usuarios
        User::factory()->createMany([
            [
                'name' => 'fabian',
                'email' => 'panamenofabian@gmail.com',
                'password' => Hash::make('1824'), // <--- contraseña encriptada
                'role_id' => 1,
            ],
            [
                'name' => 'juancarlos',
                'email' => 'juancarlos@gmail.com',
                'password' => Hash::make('12345'),
                'role_id' => 2,
            ],
            [
                'name' => 'luis',
                'email' => 'luis@gmail.com',
                'password' => Hash::make('12345'),
                'role_id' => 3,
            ]
        ]);
    }
}
