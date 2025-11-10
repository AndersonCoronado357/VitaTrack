<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = [

            // Medications
            ['name' => 'showMedications', 'description' => 'Ver Medicamentos', 'module' => 'Medicamentos'],
            ['name' => 'createMedications', 'description' => 'Crear Medicamentos', 'module' => 'Medicamentos'],
            ['name' => 'updateMedications', 'description' => 'Editar Medicamentos', 'module' => 'Medicamentos'],
            ['name' => 'deleteMedications', 'description' => 'Eliminar Medicamentos', 'module' => 'Medicamentos'],

            // Notes
            ['name' => 'showNotes', 'description' => 'Ver Notas', 'module' => 'Notas'],
            ['name' => 'createNotes', 'description' => 'Crear Notas', 'module' => 'Notas'],
            ['name' => 'updateNotes', 'description' => 'Editar Notas', 'module' => 'Notas'],
            ['name' => 'deleteNotes', 'description' => 'Eliminar Notas', 'module' => 'Notas'],

            //
            // ['name' => 'show', 'description' => 'Ver ', 'module' => ''],
            // ['name' => 'create', 'description' => 'Crear ', 'module' => ''],
            // ['name' => 'update', 'description' => 'Editar ', 'module' => ''],
            // ['name' => 'delete', 'description' => 'Eliminar ', 'module' => ''],

        ];

        foreach($permissions as $permission) {

            $tmpPermission = Permission::where('name', '=', $permission['name'])
                                       ->where('module', '=', $permission['module'])
                                       ->first();

            if (empty($tmpPermission)) {

                $newPermission = new Permission();
                $newPermission->name = $permission["name"];
                $newPermission->description = $permission["description"];
                $newPermission->module = $permission["module"];
                $newPermission->save();
            }
        }

    }
}
