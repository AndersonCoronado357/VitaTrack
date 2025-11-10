<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Admin
        $adminRole = new Role();
        $adminRole->name = 'Administrador';
        $adminRole->save();

        // Notes role
        $notesRole = new Role();
        $notesRole->name = 'Gestor de notas';
        $notesRole->save();

        $notePermissions = Permission::where('module', '=', 'Notas')
                                    ->get();

        foreach ($notePermissions as $permission) {
            $rolePermission = new RolePermission();
            $rolePermission->role_id = $notesRole->id;
            $rolePermission->permission_id = $permission->id;
            $rolePermission->save();
        }

        // Medications role
        $medicationsRole = new Role();
        $medicationsRole->name = 'Gestor de medicamentos';
        $medicationsRole->save();

        $medicationPermissions = Permission::where('module', '=', 'Medicamentos')
                                        ->get();

        foreach ($medicationPermissions as $permission) {
            $rolePermission = new RolePermission();
            $rolePermission->role_id = $medicationsRole->id;
            $rolePermission->permission_id = $permission->id;
            $rolePermission->save();
        }

        // Users
        $user = new User();
        $user->first_name = 'Anderson';
        $user->last_name = 'Coronado';
        $user->document = '123456789';
        $user->email = 'ac357844@gmail.com';
        $user->email_verified_at = now();
        $user->password = Hash::make('1234');
        $user->role_id = $adminRole->id;
        $user->save();

        $user = new User();
        $user->first_name = 'Jhon';
        $user->last_name = 'Doe';
        $user->document = '22222';
        $user->email = 'jhopd@yopmail.com';
        $user->email_verified_at = now();
        $user->password = Hash::make('1234');
        $user->role_id = $notesRole->id;
        $user->save();

        $user = new User();
        $user->first_name = 'Ana';
        $user->last_name = 'Doe';
        $user->document = '333333';
        $user->email = 'anad@yopmail.com';
        $user->email_verified_at = now();
        $user->password = Hash::make('1234');
        $user->role_id = $medicationsRole->id;
        $user->save();

    }
}
