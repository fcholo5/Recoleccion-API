<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
                'name' => 'Test Administrador',
                'email' => 'admin@example.com',
                'role_id' => 1,
            ],
            [
                'name' => 'Test Conductor',
                'email' => 'driver@example.com',
                'role_id' => 2,
            ],
            [
                'name' => 'Test Cliente',
                'email' => 'client@example.com',
                'role_id' => 3,
            ]
        ]);
    }
}
