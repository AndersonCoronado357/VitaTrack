<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class HabitsController extends Controller
{
    public function index(Request $request)
    {
        if (!empty($request->records_per_page)) {
            $request->records_per_page = $request->records_per_page <= env('PAGINATION_MAX_SIZE')
                ? $request->records_per_page
                : env('PAGINATION_MAX_SIZE');
        } else {
            $request->records_per_page = env('PAGINATION_DEFAULT_SIZE');
        }

        $habits = Habit::where('user_id', Auth::id())
            ->where('name', 'LIKE', "%$request->filter%")
            ->orderBy('active', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($request->records_per_page);

        return view('habits/index', ['habits' => $habits, 'data' => $request]);
    }

    public function create()
    {
        return view('habits/create');
    }

    public function store(Request $request)
    {
        // Limpiar campos vacíos antes de validar
        $data = $request->all();

        if (empty($data['reminder_time'])) {
            $data['reminder_time'] = null;
        }

        if (empty($data['end_date'])) {
            $data['end_date'] = null;
        }

        $validator = Validator::make($data, [
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'frequency' => 'required|in:daily,weekly,monthly',
            'goal_count' => 'required|integer|min:1',
            'reminder_time' => 'nullable|date_format:H:i',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'name.required' => 'El nombre del hábito es obligatorio.',
            'frequency.required' => 'La frecuencia es obligatoria.',
            'frequency.in' => 'La frecuencia debe ser diaria, semanal o mensual.',
            'goal_count.required' => 'La meta es obligatoria.',
            'goal_count.min' => 'La meta debe ser al menos 1.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'reminder_time.date_format' => 'La hora de recordatorio debe tener formato HH:MM (ej: 07:30).',
            'end_date.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la de inicio.',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed in store', ['errors' => $validator->errors()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $habit = new Habit();
            $habit->user_id = Auth::id();
            $habit->name = $request->name;
            $habit->description = $request->description;
            $habit->frequency = $request->frequency;
            $habit->goal_count = $request->goal_count;
            $habit->reminder_time = $data['reminder_time'];
            $habit->color = $request->color ?? '#0d6efd';
            $habit->icon = $request->icon ?? 'bi-check-circle';
            $habit->start_date = $request->start_date;
            $habit->end_date = $data['end_date'];
            $habit->active = true;
            $habit->save();

            Log::info('Habit created successfully', ['habit_id' => $habit->id]);

            Session::flash('message', ['content' => 'Hábito creado con éxito', 'type' => 'success']);
            return redirect()->route('habits.index');
        } catch (Exception $ex) {
            Log::error('Error creating habit', [
                'message' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString()
            ]);
            Session::flash('message', ['content' => 'Ha ocurrido un error: ' . $ex->getMessage(), 'type' => 'error']);
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $habit = Habit::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (empty($habit)) {
            Log::warning('Habit not found for edit', ['habit_id' => $id, 'user_id' => Auth::id()]);
            Session::flash('message', ['content' => "El hábito no existe o no tienes permiso para editarlo.", 'type' => 'error']);
            return redirect()->back();
        }

        return view('habits/edit', ['habit' => $habit]);
    }

    public function update(Request $request)
    {
        Log::info('Update request received', ['data' => $request->all()]);

        // Limpiar campos vacíos antes de validar
        $data = $request->all();

        // Si reminder_time está vacío, convertirlo a null
        if (empty($data['reminder_time'])) {
            $data['reminder_time'] = null;
        }

        // Si end_date está vacío, convertirlo a null
        if (empty($data['end_date'])) {
            $data['end_date'] = null;
        }

        $validator = Validator::make($data, [
            'habit_id' => 'required|exists:habits,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'frequency' => 'required|in:daily,weekly,monthly',
            'goal_count' => 'required|integer|min:1',
            'reminder_time' => 'nullable|date_format:H:i',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'habit_id.required' => 'El ID del hábito es obligatorio.',
            'habit_id.exists' => 'El hábito indicado no existe.',
            'name.required' => 'El nombre del hábito es obligatorio.',
            'frequency.required' => 'La frecuencia es obligatoria.',
            'goal_count.required' => 'La meta es obligatoria.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'reminder_time.date_format' => 'La hora de recordatorio debe tener formato HH:MM (ej: 07:30).',
            'end_date.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la de inicio.',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed in update', ['errors' => $validator->errors()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $habit = Habit::where('id', $request->habit_id)
                ->where('user_id', Auth::id())
                ->first();

            if (empty($habit)) {
                Log::warning('Habit not found for update', ['habit_id' => $request->habit_id, 'user_id' => Auth::id()]);
                Session::flash('message', ['content' => 'No tienes permiso para editar este hábito.', 'type' => 'error']);
                return redirect()->back();
            }

            $habit->name = $request->name;
            $habit->description = $request->description;
            $habit->frequency = $request->frequency;
            $habit->goal_count = $request->goal_count;
            $habit->reminder_time = $data['reminder_time'];
            $habit->color = $request->color ?? '#0d6efd';
            $habit->icon = $request->icon ?? 'bi-check-circle';
            $habit->start_date = $request->start_date;
            $habit->end_date = $data['end_date'];
            $habit->active = $request->has('active') ? 1 : 0;

            $saved = $habit->save();

            Log::info('Habit updated', ['habit_id' => $habit->id, 'saved' => $saved]);

            Session::flash('message', ['content' => 'Hábito actualizado con éxito', 'type' => 'success']);
            return redirect()->route('habits.index');
        } catch (Exception $ex) {
            Log::error('Error updating habit', [
                'message' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString()
            ]);
            Session::flash('message', ['content' => 'Ha ocurrido un error: ' . $ex->getMessage(), 'type' => 'error']);
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $habit = Habit::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();

            if (empty($habit)) {
                Session::flash('message', ['content' => "El hábito no existe o no tienes permiso para eliminarlo.", 'type' => 'error']);
                return redirect()->back();
            }

            $habit->delete();

            Session::flash('message', ['content' => 'Hábito eliminado con éxito', 'type' => 'success']);
            return redirect()->route('habits.index');
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back();
        }
    }

    public function logCompletion(Request $request)
    {
        Log::info('Log completion request', ['data' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'habit_id' => 'required|exists:habits,id',
            'completion_date' => 'required|date',
            'count' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed in logCompletion', ['errors' => $validator->errors()]);
            return response()->json(['success' => false, 'message' => 'Datos inválidos', 'errors' => $validator->errors()], 422);
        }

        try {
            $habit = Habit::where('id', $request->habit_id)
                ->where('user_id', Auth::id())
                ->first();

            if (empty($habit)) {
                Log::warning('Habit not found for logging', ['habit_id' => $request->habit_id]);
                return response()->json(['success' => false, 'message' => 'Hábito no encontrado'], 404);
            }

            $log = HabitLog::updateOrCreate(
                [
                    'habit_id' => $request->habit_id,
                    'completion_date' => $request->completion_date,
                ],
                [
                    'user_id' => Auth::id(),
                    'count' => $request->count ?? 1,
                    'notes' => $request->notes,
                ]
            );

            Log::info('Habit log created/updated', ['log_id' => $log->id, 'habit_id' => $habit->id]);

            return response()->json([
                'success' => true,
                'message' => 'Registro actualizado',
                'completed' => $log->count >= $habit->goal_count,
                'progress' => $log->count,
                'goal' => $habit->goal_count
            ]);
        } catch (Exception $ex) {
            Log::error('Error logging completion', [
                'message' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Error al registrar: ' . $ex->getMessage()], 500);
        }
    }

    public function statistics($id)
    {
        $habit = Habit::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (empty($habit)) {
            Session::flash('message', ['content' => "El hábito no existe o no tienes permiso para verlo.", 'type' => 'error']);
            return redirect()->back();
        }

        // Obtener TODOS los logs del hábito
        $allLogs = HabitLog::where('habit_id', $id)
            ->orderBy('completion_date', 'desc')
            ->get();

        // Crear un mapa de logs indexado por fecha
        $logsMap = [];
        foreach ($allLogs as $log) {
            $dateKey = Carbon::parse($log->completion_date)->format('Y-m-d');
            $logsMap[$dateKey] = $log->count;
        }

        // Preparar datos para la gráfica (últimos 30 días)
        $chartLabels = [];
        $chartData = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateKey = $date->format('Y-m-d');

            $chartLabels[] = $date->format('d/m');
            $chartData[] = isset($logsMap[$dateKey]) ? $logsMap[$dateKey] : 0;
        }

        // Logs para la tabla (últimos 30 días)
        $logs = HabitLog::where('habit_id', $id)
            ->where('completion_date', '>=', Carbon::today()->subDays(29))
            ->orderBy('completion_date', 'desc')
            ->get();

        Log::info('Statistics data prepared', [
            'habit_id' => $id,
            'all_logs_count' => $allLogs->count(),
            'logs_map_count' => count($logsMap),
            'chart_data_sample' => array_slice($chartData, 0, 5),
            'logs_map_sample' => array_slice($logsMap, 0, 5, true)
        ]);

        // Calcular estadísticas
        $stats = [
            'current_streak' => $habit->getCurrentStreak(),
            'completion_rate_7' => $habit->getCompletionRate(7),
            'completion_rate_30' => $habit->getCompletionRate(30),
            'total_completions' => $habit->logs()->where('count', '>=', $habit->goal_count)->count(),
            'best_streak' => $this->calculateBestStreak($habit),
        ];

        return view('habits/statistics', [
            'habit' => $habit,
            'logs' => $logs,
            'stats' => $stats,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData
        ]);
    }

    private function calculateBestStreak(Habit $habit)
    {
        $logs = $habit->logs()
            ->where('count', '>=', $habit->goal_count)
            ->orderBy('completion_date', 'asc')
            ->pluck('completion_date')
            ->toArray();

        if (empty($logs)) {
            return 0;
        }

        $bestStreak = 1;
        $currentStreak = 1;

        for ($i = 1; $i < count($logs); $i++) {
            $prevDate = Carbon::parse($logs[$i - 1]);
            $currDate = Carbon::parse($logs[$i]);

            if ($prevDate->diffInDays($currDate) === 1) {
                $currentStreak++;
                $bestStreak = max($bestStreak, $currentStreak);
            } else {
                $currentStreak = 1;
            }
        }

        return $bestStreak;
    }
}
