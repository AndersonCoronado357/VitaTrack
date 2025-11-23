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

            // Users
            ['name' => 'showUsers', 'description' => 'Ver Usuarios', 'module' => 'Usuarios'],
            ['name' => 'createUsers', 'description' => 'Crear Usuarios', 'module' => 'Usuarios'],
            ['name' => 'updateUsers', 'description' => 'Editar Usuarios', 'module' => 'Usuarios'],
            ['name' => 'deleteUsers', 'description' => 'Eliminar Usuarios', 'module' => 'Usuarios'],

            // Roles
            ['name' => 'showRoles', 'description' => 'Ver Roles', 'module' => 'Roles'],
            ['name' => 'createRoles', 'description' => 'Crear Roles', 'module' => 'Roles'],
            ['name' => 'updateRoles', 'description' => 'Actualizar Roles', 'module' => 'Roles'],
            ['name' => 'deleteRoles', 'description' => 'Eliminar Roles', 'module' => 'Roles'],

            // Habits
            ['name' => 'showHabits', 'description' => 'Ver Hábitos', 'module' => 'Hábitos'],
            ['name' => 'createHabits', 'description' => 'Crear Hábitos', 'module' => 'Hábitos'],
            ['name' => 'updateHabits', 'description' => 'Editar Hábitos', 'module' => 'Hábitos'],
            ['name' => 'deleteHabits', 'description' => 'Eliminar Hábitos', 'module' => 'Hábitos'],
            ['name' => 'logHabits', 'description' => 'Registrar Cumplimiento', 'module' => 'Hábitos'],
            ['name' => 'statsHabits', 'description' => 'Ver Estadísticas', 'module' => 'Hábitos'],

            // Nutrition
            ['name' => 'showNutrition', 'description' => 'Ver Nutrición', 'module' => 'Nutrición'],
            ['name' => 'createNutrition', 'description' => 'Registrar Comidas', 'module' => 'Nutrición'],
            ['name' => 'updateNutrition', 'description' => 'Editar Registros', 'module' => 'Nutrición'],
            ['name' => 'deleteNutrition', 'description' => 'Eliminar Registros', 'module' => 'Nutrición'],
            ['name' => 'historyNutrition', 'description' => 'Ver Historial', 'module' => 'Nutrición'],
            ['name' => 'statsNutrition', 'description' => 'Ver Estadísticas', 'module' => 'Nutrición'],
            ['name' => 'goalsNutrition', 'description' => 'Gestionar Metas', 'module' => 'Nutrición'],

            // Health Metrics
            ['name' => 'showHealthMetrics', 'description' => 'Ver Métricas', 'module' => 'Métricas de Salud'],
            ['name' => 'createHealthMetrics', 'description' => 'Registrar Métricas', 'module' => 'Métricas de Salud'],
            ['name' => 'updateHealthMetrics', 'description' => 'Editar Métricas', 'module' => 'Métricas de Salud'],
            ['name' => 'deleteHealthMetrics', 'description' => 'Eliminar Métricas', 'module' => 'Métricas de Salud'],
            ['name' => 'statsHealthMetrics', 'description' => 'Ver Estadísticas', 'module' => 'Métricas de Salud'],
            ['name' => 'rangesHealthMetrics', 'description' => 'Configurar Rangos', 'module' => 'Métricas de Salud'],

            // Sleep
            ['name' => 'showSleep', 'description' => 'Ver Sueño', 'module' => 'Sueño y Descanso'],
            ['name' => 'createSleep', 'description' => 'Registrar Sueño', 'module' => 'Sueño y Descanso'],
            ['name' => 'updateSleep', 'description' => 'Editar Registros', 'module' => 'Sueño y Descanso'],
            ['name' => 'deleteSleep', 'description' => 'Eliminar Registros', 'module' => 'Sueño y Descanso'],
            ['name' => 'historySleep', 'description' => 'Ver Historial', 'module' => 'Sueño y Descanso'],
            ['name' => 'statsSleep', 'description' => 'Ver Estadísticas', 'module' => 'Sueño y Descanso'],
            ['name' => 'goalsSleep', 'description' => 'Gestionar Metas', 'module' => 'Sueño y Descanso'],

            // Appointments
            ['name' => 'showAppointments', 'description' => 'Ver Citas', 'module' => 'Citas y Calendario'],
            ['name' => 'createAppointments', 'description' => 'Crear Citas', 'module' => 'Citas y Calendario'],
            ['name' => 'updateAppointments', 'description' => 'Editar Citas', 'module' => 'Citas y Calendario'],
            ['name' => 'deleteAppointments', 'description' => 'Eliminar Citas', 'module' => 'Citas y Calendario'],
            ['name' => 'calendarAppointments', 'description' => 'Ver Calendario', 'module' => 'Citas y Calendario'],

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
