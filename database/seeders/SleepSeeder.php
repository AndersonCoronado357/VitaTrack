<?php

namespace Database\Seeders;

use App\Models\SleepRecord;
use App\Models\SleepGoal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SleepSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener el primer usuario
        $user = User::first();

        if (!$user) {
            $this->command->error('No hay usuarios en la base de datos');
            return;
        }

        // Crear meta de sueño para el usuario
        SleepGoal::updateOrCreate(
            ['user_id' => $user->id],
            [
                'target_hours' => 8.0,
                'target_bedtime' => '22:30',
                'target_wake_time' => '06:30',
                'max_interruptions' => 2
            ]
        );

        $recordsCreated = 0;

        // Generar registros para los últimos 60 días
        for ($i = 60; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            // 85% de probabilidad de tener registro ese día
            if (rand(1, 100) <= 85) {

                // Generar hora de acostarse (entre 21:00 y 00:30)
                $bedtimeHour = rand(21, 24);
                if ($bedtimeHour === 24) {
                    $bedtimeHour = 0;
                    $bedtimeMinute = rand(0, 30);
                } else {
                    $bedtimeMinute = rand(0, 59);
                }
                $bedtime = sprintf('%02d:%02d', $bedtimeHour, $bedtimeMinute);

                // Generar hora de despertar (entre 05:30 y 08:30)
                $wakeHour = rand(5, 8);
                if ($wakeHour === 5) {
                    $wakeMinute = rand(30, 59);
                } else {
                    $wakeMinute = rand(0, 59);
                }
                $wakeTime = sprintf('%02d:%02d', $wakeHour, $wakeMinute);

                // Calcular horas totales
                $totalHours = SleepRecord::calculateTotalHours($bedtime, $wakeTime);

                // Generar interrupciones (con distribución realista)
                $interruptions = $this->generateInterruptions($totalHours);

                // Determinar calidad basada en horas e interrupciones
                $quality = SleepRecord::determineQuality($totalHours, $interruptions, 8.0);

                // Determinar si se sintió descansado
                $feltRested = false;
                if ($quality === 'excellent') {
                    $feltRested = rand(1, 100) <= 90; // 90% de probabilidad
                } elseif ($quality === 'good') {
                    $feltRested = rand(1, 100) <= 70; // 70% de probabilidad
                } elseif ($quality === 'fair') {
                    $feltRested = rand(1, 100) <= 40; // 40% de probabilidad
                } else {
                    $feltRested = rand(1, 100) <= 10; // 10% de probabilidad
                }

                // Generar notas ocasionales
                $notes = null;
                if (rand(1, 100) <= 30) {
                    $notes = $this->getRandomNote($quality, $interruptions);
                }

                SleepRecord::create([
                    'user_id' => $user->id,
                    'sleep_date' => $date,
                    'bedtime' => $bedtime,
                    'wake_time' => $wakeTime,
                    'total_hours' => $totalHours,
                    'interruptions' => $interruptions,
                    'quality' => $quality,
                    'felt_rested' => $feltRested,
                    'notes' => $notes,
                ]);

                $recordsCreated++;
            }
        }

        $this->command->info('✓ Meta de sueño creada para el usuario: ' . $user->email);
        $this->command->info('✓ Se crearon ' . $recordsCreated . ' registros de sueño');
        $this->command->info('✓ Período: últimos 60 días con variación realista');
        $this->command->info('✓ Meta: 8 horas | Horario: 22:30 - 06:30');
    }

    /**
     * Genera número de interrupciones basado en horas de sueño
     */
    private function generateInterruptions($totalHours)
    {
        // Más interrupciones si durmió poco o mucho
        if ($totalHours < 6 || $totalHours > 10) {
            $weights = [0 => 20, 1 => 30, 2 => 25, 3 => 15, 4 => 7, 5 => 3];
        } else {
            $weights = [0 => 50, 1 => 30, 2 => 15, 3 => 4, 4 => 1];
        }

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($weights as $interruptions => $probability) {
            $cumulative += $probability;
            if ($rand <= $cumulative) {
                return $interruptions;
            }
        }

        return 0;
    }

    /**
     * Genera notas aleatorias según calidad e interrupciones
     */
    private function getRandomNote($quality, $interruptions)
    {
        $notes = [
            'excellent' => [
                'Dormí profundamente toda la noche',
                'Me desperté renovado/a',
                'Excelente descanso',
                'Sueño reparador',
                'Sin molestias durante la noche',
            ],
            'good' => [
                'Buen descanso en general',
                'Me sentí bien al despertar',
                'Dormí bien',
                'Noche tranquila',
                'Desperté solo con la alarma',
            ],
            'fair' => [
                'Tuve algunos despertares',
                'Costó conciliar el sueño',
                'Sueño ligero',
                'Me desperté varias veces',
                'No dormí tan profundo como quisiera',
            ],
            'poor' => [
                'Mala noche, muchas interrupciones',
                'No pude dormir bien',
                'Me costó mucho conciliar el sueño',
                'Desperté cansado/a',
                'Pesadillas',
                'Insomnio',
            ],
        ];

        if ($interruptions > 3) {
            $extraNotes = [
                'Ruido en la calle',
                'Dolor de espalda',
                'Preocupaciones',
                'Calor en la habitación',
                'Frío durante la noche',
            ];
            return $extraNotes[array_rand($extraNotes)];
        }

        $qualityNotes = $notes[$quality] ?? $notes['fair'];
        return $qualityNotes[array_rand($qualityNotes)];
    }
}
