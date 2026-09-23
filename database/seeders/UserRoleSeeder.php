<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Solicitante (Enfermería)
        User::updateOrCreate(
            ['email' => 'enfermeria@villaplata.com'],
            [
                'name'     => 'Enfermería Piso',
                'password' => Hash::make('12345678'),
                'role'     => 'solicitante',
            ]
        );

        // 2. Supervisor (Almacén)
        User::updateOrCreate(
            ['email' => 'almacen@villaplata.com'],
            [
                'name'     => 'Supervisor Almacén',
                'password' => Hash::make('12345678'),
                'role'     => 'supervisor',
            ]
        );

        // 3. Administrador
        User::updateOrCreate(
            ['email' => 'admin@villaplata.com'],
            [
                'name'     => 'Administrador Sistema',
                'password' => Hash::make('12345678'),
                'role'     => 'admin',
            ]
        );
    }
}