<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentsSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener el primer usuario
        $user = User::first();

        if (!$user) {
            $this->command->error('No hay usuarios en la base de datos');
            return;
        }

        $appointmentsCreated = 0;

        // Datos de ejemplo para citas médicas
        $medicalAppointments = [
            ['title' => 'Consulta General', 'doctor' => 'García Pérez', 'specialty' => 'Medicina General'],
            ['title' => 'Control Cardiológico', 'doctor' => 'Martínez López', 'specialty' => 'Cardiología'],
            ['title' => 'Revisión Dermatológica', 'doctor' => 'Rodríguez Sánchez', 'specialty' => 'Dermatología'],
            ['title' => 'Examen Oftalmológico', 'doctor' => 'Fernández Torres', 'specialty' => 'Oftalmología'],
            ['title' => 'Control de Glucosa', 'doctor' => 'López Ramírez', 'specialty' => 'Endocrinología'],
            ['title' => 'Revisión Odontológica', 'doctor' => 'Gómez Vargas', 'specialty' => 'Odontología'],
            ['title' => 'Análisis de Sangre', 'doctor' => null, 'specialty' => 'Laboratorio'],
            ['title' => 'Fisioterapia', 'doctor' => 'Pérez Morales', 'specialty' => 'Fisioterapia'],
            ['title' => 'Control Nutricional', 'doctor' => 'Silva Castro', 'specialty' => 'Nutrición'],
            ['title' => 'Vacunación', 'doctor' => null, 'specialty' => 'Inmunología'],
        ];

        $personalAppointments = [
            'Corte de cabello',
            'Cita en el banco',
            'Reunión familiar',
            'Cumpleaños de amigo',
            'Cita con abogado',
            'Renovación de documentos',
            'Entrega de paquete',
            'Clase de yoga',
            'Cita en el gimnasio',
            'Reunión de vecinos',
        ];

        $workAppointments = [
            'Reunión con cliente',
            'Presentación de proyecto',
            'Entrevista de trabajo',
            'Capacitación',
            'Reunión de equipo',
            'Revisión de desempeño',
            'Videoconferencia internacional',
            'Workshop',
            'Firma de contrato',
            'Auditoría',
        ];

        $locations = [
            'Hospital Central - Consultorio 305',
            'Clínica Santa María - Piso 2',
            'Centro Médico Del Valle',
            'Consultorio Privado - Calle 50 #23-45',
            'Hospital San José - Torre B',
            'Centro de Especialistas',
            'Laboratorio Clínico',
            'Oficina Principal',
            'Centro Comercial - Local 203',
            'Sede Norte',
        ];

        $colors = ['#0d6efd', '#198754', '#dc3545', '#ffc107', '#0dcaf0', '#6f42c1', '#fd7e14', '#20c997'];

        // Generar citas pasadas (últimos 60 días)
        for ($i = 60; $i >= 1; $i--) {
            $date = Carbon::today()->subDays($i);

            // 40% de probabilidad de tener cita en días pasados
            if (rand(1, 100) <= 40) {
                $type = $this->getRandomType();

                if ($type === 'medical') {
                    $medical = $medicalAppointments[array_rand($medicalAppointments)];
                    $title = $medical['title'];
                    $doctor = $medical['doctor'];
                    $specialty = $medical['specialty'];
                    $duration = rand(2, 6) * 15; // 30, 45, 60, 75, 90 minutos
                } elseif ($type === 'personal') {
                    $title = $personalAppointments[array_rand($personalAppointments)];
                    $doctor = null;
                    $specialty = null;
                    $duration = rand(1, 4) * 30; // 30, 60, 90, 120 minutos
                } elseif ($type === 'work') {
                    $title = $workAppointments[array_rand($workAppointments)];
                    $doctor = null;
                    $specialty = null;
                    $duration = rand(2, 8) * 15; // 30-120 minutos
                } else {
                    $title = 'Evento importante';
                    $doctor = null;
                    $specialty = null;
                    $duration = 60;
                }

                // Hora aleatoria entre 8:00 y 17:00
                $hour = rand(8, 17);
                $minute = rand(0, 3) * 15; // 00, 15, 30, 45
                $time = sprintf('%02d:%02d', $hour, $minute);

                // Estados para citas pasadas
                $statuses = ['completed' => 70, 'cancelled' => 20, 'scheduled' => 10];
                $status = $this->getWeightedRandom($statuses);

                Appointment::create([
                    'user_id' => $user->id,
                    'title' => $title,
                    'description' => rand(1, 100) <= 30 ? $this->getDescription($type) : null,
                    'type' => $type,
                    'location' => rand(1, 100) <= 80 ? $locations[array_rand($locations)] : null,
                    'appointment_date' => $date,
                    'appointment_time' => $time,
                    'duration' => $duration,
                    'doctor_name' => $doctor,
                    'specialty' => $specialty,
                    'status' => $status,
                    'reminder_enabled' => rand(1, 100) <= 80,
                    'reminder_minutes' => [15, 30, 60, 120, 1440][array_rand([15, 30, 60, 120, 1440])],
                    'notes' => rand(1, 100) <= 25 ? $this->getNotes($status) : null,
                    'color' => $colors[array_rand($colors)],
                ]);

                $appointmentsCreated++;
            }
        }

        // Generar citas futuras (próximos 90 días)
        for ($i = 0; $i <= 90; $i++) {
            $date = Carbon::today()->addDays($i);

            // Más citas programadas en el futuro cercano
            $probability = $i <= 30 ? 50 : 30;

            if (rand(1, 100) <= $probability) {
                $type = $this->getRandomType();

                if ($type === 'medical') {
                    $medical = $medicalAppointments[array_rand($medicalAppointments)];
                    $title = $medical['title'];
                    $doctor = $medical['doctor'];
                    $specialty = $medical['specialty'];
                    $duration = rand(2, 6) * 15;
                } elseif ($type === 'personal') {
                    $title = $personalAppointments[array_rand($personalAppointments)];
                    $doctor = null;
                    $specialty = null;
                    $duration = rand(1, 4) * 30;
                } elseif ($type === 'work') {
                    $title = $workAppointments[array_rand($workAppointments)];
                    $doctor = null;
                    $specialty = null;
                    $duration = rand(2, 8) * 15;
                } else {
                    $title = 'Evento programado';
                    $doctor = null;
                    $specialty = null;
                    $duration = 60;
                }

                $hour = rand(8, 17);
                $minute = rand(0, 3) * 15;
                $time = sprintf('%02d:%02d', $hour, $minute);

                // Citas futuras principalmente programadas
                $statuses = ['scheduled' => 90, 'rescheduled' => 10];
                $status = $this->getWeightedRandom($statuses);

                Appointment::create([
                    'user_id' => $user->id,
                    'title' => $title,
                    'description' => rand(1, 100) <= 30 ? $this->getDescription($type) : null,
                    'type' => $type,
                    'location' => rand(1, 100) <= 80 ? $locations[array_rand($locations)] : null,
                    'appointment_date' => $date,
                    'appointment_time' => $time,
                    'duration' => $duration,
                    'doctor_name' => $doctor,
                    'specialty' => $specialty,
                    'status' => $status,
                    'reminder_enabled' => rand(1, 100) <= 90, // Más recordatorios para futuras
                    'reminder_minutes' => [60, 120, 1440][array_rand([60, 120, 1440])], // 1h, 2h, 1 día
                    'notes' => rand(1, 100) <= 20 ? $this->getNotes($status) : null,
                    'color' => $colors[array_rand($colors)],
                ]);

                $appointmentsCreated++;
            }
        }

        // Asegurar que hay al menos 2 citas hoy
        $todayAppointments = Appointment::where('user_id', $user->id)
            ->where('appointment_date', Carbon::today())
            ->count();

        if ($todayAppointments < 2) {
            for ($j = 0; $j < (2 - $todayAppointments); $j++) {
                $medical = $medicalAppointments[array_rand($medicalAppointments)];

                Appointment::create([
                    'user_id' => $user->id,
                    'title' => $medical['title'],
                    'description' => 'Cita programada para hoy',
                    'type' => 'medical',
                    'location' => $locations[array_rand($locations)],
                    'appointment_date' => Carbon::today(),
                    'appointment_time' => sprintf('%02d:%02d', rand(9, 16), rand(0, 3) * 15),
                    'duration' => 45,
                    'doctor_name' => $medical['doctor'],
                    'specialty' => $medical['specialty'],
                    'status' => 'scheduled',
                    'reminder_enabled' => true,
                    'reminder_minutes' => 60,
                    'notes' => 'Importante: Llegar 15 minutos antes',
                    'color' => '#dc3545',
                ]);
                $appointmentsCreated++;
            }
        }

        $this->command->info('✓ Se crearon ' . $appointmentsCreated . ' citas');
        $this->command->info('✓ Usuario: ' . $user->email);
        $this->command->info('✓ Período: 60 días pasados + 90 días futuros');
        $this->command->info('✓ Tipos: Médicas, Personales, Trabajo, Otros');
    }

    private function getRandomType()
    {
        $weights = ['medical' => 50, 'personal' => 25, 'work' => 20, 'other' => 5];
        return $this->getWeightedRandom($weights);
    }

    private function getWeightedRandom($weights)
    {
        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($weights as $key => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $key;
            }
        }

        return array_key_first($weights);
    }

    private function getDescription($type)
    {
        $descriptions = [
            'medical' => [
                'Control de rutina',
                'Seguimiento de tratamiento',
                'Revisión de exámenes anteriores',
                'Primera consulta',
                'Control post-operatorio',
            ],
            'personal' => [
                'Evento importante',
                'Compromiso personal',
                'Actividad programada',
                'Cita pendiente',
            ],
            'work' => [
                'Reunión estratégica',
                'Presentación de resultados',
                'Planificación trimestral',
                'Seguimiento de proyecto',
                'Videoconferencia con el equipo',
            ],
            'other' => [
                'Evento especial',
                'Compromiso importante',
                'Actividad programada',
            ],
        ];

        $list = $descriptions[$type] ?? $descriptions['other'];
        return $list[array_rand($list)];
    }

    private function getNotes($status)
    {
        $notes = [
            'scheduled' => [
                'Llevar documentos anteriores',
                'Llegar 15 minutos antes',
                'Confirmar asistencia un día antes',
                'Traer resultados de exámenes',
                'Ayuno de 8 horas',
            ],
            'completed' => [
                'Cita realizada sin novedades',
                'Próximo control en 3 meses',
                'Todo en orden',
                'Exámenes solicitados',
                'Tratamiento prescrito',
            ],
            'cancelled' => [
                'Reprogramar próximamente',
                'Cancelada por el médico',
                'Conflicto de horarios',
                'Suspendida temporalmente',
            ],
            'rescheduled' => [
                'Pendiente confirmar nueva fecha',
                'Cambio de horario solicitado',
                'Reprogramación en proceso',
            ],
        ];

        $list = $notes[$status] ?? ['Nota general'];
        return $list[array_rand($list)];
    }
}
