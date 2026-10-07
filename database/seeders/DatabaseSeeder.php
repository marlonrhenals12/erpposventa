<?php

namespace Database\Seeders;

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
        // 1. Roles
        $superadminRole = Role::firstOrCreate(
            ['name' => 'superadmin'],
            [
                'display_name' => 'Super Administrador',
                'description' => 'Acceso y control total de todos los módulos'
            ]
        );

        $vendedorRole = Role::firstOrCreate(
            ['name' => 'vendedor'],
            [
                'display_name' => 'Vendedor',
                'description' => 'Acceso exclusivo al módulo de Ventas (POS)'
            ]
        );

        // 2. Usuario Superadmin
        $superadmin = User::updateOrCreate(
            ['email' => 'superadmin@posventa.com'],
            [
                'name' => 'Super Administrador',
                'password' => Hash::make('password'),
            ]
        );
        $superadmin->roles()->sync([$superadminRole->id]);

        // También le asignamos superadmin al usuario inicial de prueba test@example.com
        $testUser = User::where('email', 'test@example.com')->first();
        if ($testUser) {
            $testUser->roles()->sync([$superadminRole->id]);
        }

        // 3. Usuario Vendedor (solo acceso al módulo de ventas)
        $vendedor = User::updateOrCreate(
            ['email' => 'vendedor@posventa.com'],
            [
                'name' => 'Carlos Vendedor',
                'password' => Hash::make('password'),
            ]
        );
        $vendedor->roles()->sync([$vendedorRole->id]);
    }
}
