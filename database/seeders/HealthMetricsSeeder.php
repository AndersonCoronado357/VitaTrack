<?php

namespace Database\Seeders;

use App\Models\HealthMetric;
use App\Models\HealthMetricRange;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HealthMetricsSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener el primer usuario
        $user = User::first();

        if (!$user) {
            $this->command->error('No hay usuarios en la base de datos');
            return;
        }

        // Crear rangos personalizados para algunas métricas
        HealthMetricRange::updateOrCreate(
            ['user_id' => $user->id, 'metric_type' => 'blood_pressure'],
            [
                'min_normal' => 90,
                'max_normal' => 120,
                'min_warning' => 70,
                'max_warning' => 140,
                'min_normal_secondary' => 60,
                'max_normal_secondary' => 80,
            ]
        );

        HealthMetricRange::updateOrCreate(
            ['user_id' => $user->id, 'metric_type' => 'glucose'],
            [
                'min_normal' => 70,
                'max_normal' => 100,
                'min_warning' => 50,
                'max_warning' => 140,
            ]
        );

        $metricsCreated = 0;

        // Generar registros para los últimos 60 días
        for ($i = 60; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            // 80% de probabilidad de tener mediciones ese día
            if (rand(1, 100) <= 80) {

                // PRESIÓN ARTERIAL (mañana y/o noche)
                // Mañana (70% de probabilidad)
                if (rand(1, 100) <= 70) {
                    $systolic = $this->generateValue(110, 130, 90, 150); // Normal con variación
                    $diastolic = $this->generateValue(70, 80, 60, 95);

                    $status = HealthMetric::determineStatus('blood_pressure', $systolic, $diastolic, $user->id);

                    HealthMetric::create([
                        'user_id' => $user->id,
                        'metric_type' => 'blood_pressure',
                        'value' => $systolic,
                        'value_secondary' => $diastolic,
                        'unit' => 'mmHg',
                        'measured_date' => $date,
                        'measured_time' => '07:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
                        'notes' => rand(1, 100) <= 30 ? $this->getRandomNote('blood_pressure') : null,
                        'status' => $status,
                    ]);
                    $metricsCreated++;
                }

                // Noche (40% de probabilidad)
                if (rand(1, 100) <= 40) {
                    $systolic = $this->generateValue(105, 125, 85, 145);
                    $diastolic = $this->generateValue(65, 78, 58, 90);

                    $status = HealthMetric::determineStatus('blood_pressure', $systolic, $diastolic, $user->id);

                    HealthMetric::create([
                        'user_id' => $user->id,
                        'metric_type' => 'blood_pressure',
                        'value' => $systolic,
                        'value_secondary' => $diastolic,
                        'unit' => 'mmHg',
                        'measured_date' => $date,
                        'measured_time' => '20:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
                        'notes' => rand(1, 100) <= 20 ? 'Antes de dormir' : null,
                        'status' => $status,
                    ]);
                    $metricsCreated++;
                }

                // GLUCOSA (solo algunos días)
                if ($i % 3 === 0) { // Cada 3 días
                    // En ayunas (mañana)
                    $glucose = $this->generateValue(80, 100, 60, 130);
                    $status = HealthMetric::determineStatus('glucose', $glucose, null, $user->id);

                    HealthMetric::create([
                        'user_id' => $user->id,
                        'metric_type' => 'glucose',
                        'value' => $glucose,
                        'unit' => 'mg/dL',
                        'measured_date' => $date,
                        'measured_time' => '07:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
                        'notes' => 'Medición en ayunas',
                        'is_fasting' => true,
                        'status' => $status,
                    ]);
                    $metricsCreated++;

                    // Post-prandial (40% de probabilidad)
                    if (rand(1, 100) <= 40) {
                        $glucosePost = $this->generateValue(100, 130, 80, 160);
                        $statusPost = HealthMetric::determineStatus('glucose', $glucosePost, null, $user->id);

                        HealthMetric::create([
                            'user_id' => $user->id,
                            'metric_type' => 'glucose',
                            'value' => $glucosePost,
                            'unit' => 'mg/dL',
                            'measured_date' => $date,
                            'measured_time' => '14:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
                            'notes' => '2 horas después de almuerzo',
                            'is_fasting' => false,
                            'status' => $statusPost,
                        ]);
                        $metricsCreated++;
                    }
                }

                // PESO (semanal - lunes)
                if ($date->dayOfWeek === 1) { // Lunes
                    $weight = $this->generateValue(68, 72, 65, 75, 0.1); // Variación gradual
                    $status = HealthMetric::determineStatus('weight', $weight, null, $user->id);

                    HealthMetric::create([
                        'user_id' => $user->id,
                        'metric_type' => 'weight',
                        'value' => $weight,
                        'unit' => 'kg',
                        'measured_date' => $date,
                        'measured_time' => '07:00',
                        'notes' => 'Peso semanal',
                        'status' => $status,
                    ]);
                    $metricsCreated++;
                }

                // FRECUENCIA CARDÍACA (algunos días)
                if (rand(1, 100) <= 50) {
                    $heartRate = $this->generateValue(65, 85, 50, 110);
                    $status = HealthMetric::determineStatus('heart_rate', $heartRate, null, $user->id);

                    HealthMetric::create([
                        'user_id' => $user->id,
                        'metric_type' => 'heart_rate',
                        'value' => $heartRate,
                        'unit' => 'bpm',
                        'measured_date' => $date,
                        'measured_time' => '08:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
                        'notes' => rand(1, 100) <= 30 ? $this->getRandomNote('heart_rate') : null,
                        'status' => $status,
                    ]);
                    $metricsCreated++;
                }

                // TEMPERATURA (solo cuando hay síntomas - 15% de probabilidad)
                if (rand(1, 100) <= 15) {
                    $temp = $this->generateValue(36.2, 37.0, 35.5, 38.5, 0.1);
                    $status = HealthMetric::determineStatus('temperature', $temp, null, $user->id);

                    HealthMetric::create([
                        'user_id' => $user->id,
                        'metric_type' => 'temperature',
                        'value' => $temp,
                        'unit' => '°C',
                        'measured_date' => $date,
                        'measured_time' => rand(8, 20) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
                        'notes' => $temp > 37.2 ? 'Fiebre leve' : 'Control rutinario',
                        'status' => $status,
                    ]);
                    $metricsCreated++;
                }

                // OXÍGENO EN SANGRE (algunos días)
                if (rand(1, 100) <= 35) {
                    $oxygen = $this->generateValue(96, 99, 92, 100);
                    $status = HealthMetric::determineStatus('oxygen', $oxygen, null, $user->id);

                    HealthMetric::create([
                        'user_id' => $user->id,
                        'metric_type' => 'oxygen',
                        'value' => $oxygen,
                        'unit' => '%',
                        'measured_date' => $date,
                        'measured_time' => rand(7, 22) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
                        'notes' => $oxygen < 95 ? 'Nivel bajo, monitorear' : null,
                        'status' => $status,
                    ]);
                    $metricsCreated++;
                }

                // COLESTEROL (mensual - primer día del mes)
                if ($date->day === 1) {
                    $cholesterol = $this->generateValue(170, 200, 150, 250);
                    $status = HealthMetric::determineStatus('cholesterol', $cholesterol, null, $user->id);

                    HealthMetric::create([
                        'user_id' => $user->id,
                        'metric_type' => 'cholesterol',
                        'value' => $cholesterol,
                        'unit' => 'mg/dL',
                        'measured_date' => $date,
                        'measured_time' => '08:00',
                        'notes' => 'Análisis mensual en ayunas',
                        'is_fasting' => true,
                        'status' => $status,
                    ]);
                    $metricsCreated++;
                }
            }
        }

        $this->command->info('✓ Rangos personalizados creados para el usuario: ' . $user->email);
        $this->command->info('✓ Se crearon ' . $metricsCreated . ' registros de métricas de salud');
        $this->command->info('✓ Período: últimos 60 días con variación realista');
        $this->command->info('✓ Métricas incluidas: Presión Arterial, Glucosa, Peso, Frecuencia Cardíaca, Temperatura, Oxígeno, Colesterol');
    }

    /**
     * Genera un valor con distribución normal alrededor del rango ideal
     */
    private function generateValue($minIdeal, $maxIdeal, $minPossible, $maxPossible, $precision = 1)
    {
        // 70% dentro del rango ideal
        if (rand(1, 100) <= 70) {
            $value = $minIdeal + (($maxIdeal - $minIdeal) * (rand(0, 1000) / 1000));
        }
        // 20% cerca de los límites del rango ideal
        elseif (rand(1, 100) <= 90) {
            if (rand(0, 1) === 0) {
                // Cerca del límite inferior
                $value = $minPossible + (($minIdeal - $minPossible) * (rand(0, 1000) / 1000));
            } else {
                // Cerca del límite superior
                $value = $maxIdeal + (($maxPossible - $maxIdeal) * (rand(0, 1000) / 1000));
            }
        }
        // 10% fuera del rango (alertas)
        else {
            if (rand(0, 1) === 0) {
                $value = $minPossible + (($minIdeal - $minPossible) * (rand(0, 500) / 1000));
            } else {
                $value = $maxIdeal + (($maxPossible - $maxIdeal) * (rand(500, 1000) / 1000));
            }
        }

        return round($value, $precision);
    }

    /**
     * Obtiene una nota aleatoria según el tipo de métrica
     */
    private function getRandomNote($metricType)
    {
        $notes = [
            'blood_pressure' => [
                'En reposo',
                'Después de caminar',
                'Tomado en casa',
                'Antes de medicación',
                'Después de medicación',
                'Me siento bien',
                'Leve dolor de cabeza',
                'Después de descansar',
            ],
            'heart_rate' => [
                'En reposo',
                'Después de ejercicio leve',
                'Al despertar',
                'Durante el día',
                'Me siento normal',
                'Un poco ansioso/a',
            ],
        ];

        $list = $notes[$metricType] ?? ['Medición rutinaria'];
        return $list[array_rand($list)];
    }
}
