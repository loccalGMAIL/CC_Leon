<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class RolesyPermisos extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear roles
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'usuario', 'guard_name' => 'web']);
        Role::create(['name' => 'supervisor', 'guard_name' => 'web']);

        // Crear usuario administrador
        $user = Usuario::create([
            'nombreUsuario' => 'Admin',
            'apellidoUsuario' => 'Sistema',
            'dniUsuario' => '00000000',
            'mailUsuario' => 'admin@sistema.com',
            'usser' => 'admin',
            'pass' => Hash::make('password'),
            'perfilUsuario' => 'admin'
        ]);

        $user->assignRole('admin');
    }
}