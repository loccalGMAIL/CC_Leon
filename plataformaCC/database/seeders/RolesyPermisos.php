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
        $admin = Role::create(['name' => 'admin']);
        $usuario = Role::create(['name' => 'usuario']);
        $supervisor = Role::create(['name' => 'supervisor']);

        // Crear permisos básicos
        $permissions = [
            'ver-dashboard',
            'gestionar-usuarios',
            'gestionar-proveedores',
            'gestionar-camiones',
            'gestionar-rto',
            'ver-reportes',
            'editar-rto',
            'eliminar-rto'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Asignar todos los permisos al admin
        $admin->givePermissionTo(Permission::all());
        
        // Asignar permisos básicos al usuario
        $usuario->givePermissionTo([
            'ver-dashboard',
            'ver-reportes'
        ]);
        
        // Asignar permisos al supervisor
        $supervisor->givePermissionTo([
            'ver-dashboard',
            'gestionar-rto',
            'ver-reportes',
            'editar-rto'
        ]);

        
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