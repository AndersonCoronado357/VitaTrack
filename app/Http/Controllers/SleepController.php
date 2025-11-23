<?php

namespace App\Http\Controllers;

use App\Models\SleepRecord;
use App\Models\SleepGoal;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SleepController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? Carbon::today()->format('Y-m-d');
        $startOfWeek = Carbon::parse($date)->startOfWeek();
        $endOfWeek = Carbon::parse($date)->endOfWeek();

        $records = SleepRecord::where('user_id', Auth::id())
            ->whereBetween('sleep_date', [$startOfWeek, $endOfWeek])
            ->orderBy('sleep_date', 'desc')
            ->get();

        $goal = SleepGoal::getOrCreateForUser(Auth::id());

        // Estadísticas de la semana
        $weekStats = [
            'avg_hours' => $records->avg('total_hours'),
            'total_nights' => $records->count(),
            'avg_interruptions' => $records->avg('interruptions'),
            'nights_met_goal' => $records->where('total_hours', '>=', $goal->target_hours - 0.5)->count(),
        ];

        return view('sleep/index', [
            'records' => $records,
            'goal' => $goal,
            'weekStats' => $weekStats,
            'currentDate' => $date,
            'startOfWeek' => $startOfWeek,
            'endOfWeek' => $endOfWeek
        ]);
    }

    public function create(Request $request)
    {
        $date = $request->date ?? Carbon::yesterday()->format('Y-m-d');
        return view('sleep/create', ['date' => $date]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sleep_date' => 'required|date',
            'bedtime' => 'required|date_format:H:i',
            'wake_time' => 'required|date_format:H:i',
            'interruptions' => 'required|integer|min:0|max:20',
            'quality' => 'required|in:excellent,good,fair,poor',
            'felt_rested' => 'nullable|boolean',
            'notes' => 'nullable|string|max:500'
        ], [
            'sleep_date.required' => 'La fecha es obligatoria.',
            'bedtime.required' => 'La hora de acostarse es obligatoria.',
            'wake_time.required' => 'La hora de despertar es obligatoria.',
            'interruptions.required' => 'El número de interrupciones es obligatorio.',
            'quality.required' => 'La calidad del sueño es obligatoria.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Calcular horas totales
            $totalHours = SleepRecord::calculateTotalHours($request->bedtime, $request->wake_time);

            $record = new SleepRecord();
            $record->user_id = Auth::id();
            $record->sleep_date = $request->sleep_date;
            $record->bedtime = $request->bedtime;
            $record->wake_time = $request->wake_time;
            $record->total_hours = $totalHours;
            $record->interruptions = $request->interruptions;
            $record->quality = $request->quality;
            $record->felt_rested = $request->has('felt_rested') ? 1 : 0;
            $record->notes = $request->notes;
            $record->save();

            Session::flash('message', ['content' => 'Registro de sueño guardado con éxito', 'type' => 'success']);
            return redirect()->route('sleep.index', ['date' => $request->sleep_date]);
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $record = SleepRecord::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (empty($record)) {
            Session::flash('message', ['content' => "El registro no existe o no tienes permiso para editarlo.", 'type' => 'error']);
            return redirect()->back();
        }

        return view('sleep/edit', ['record' => $record]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'record_id' => 'required|exists:sleep_records,id',
            'sleep_date' => 'required|date',
            'bedtime' => 'required|date_format:H:i',
            'wake_time' => 'required|date_format:H:i',
            'interruptions' => 'required|integer|min:0|max:20',
            'quality' => 'required|in:excellent,good,fair,poor',
            'felt_rested' => 'nullable|boolean',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $record = SleepRecord::where('id', $request->record_id)
                ->where('user_id', Auth::id())
                ->first();

            if (empty($record)) {
                Session::flash('message', ['content' => 'No tienes permiso para editar este registro.', 'type' => 'error']);
                return redirect()->back();
            }

            $totalHours = SleepRecord::calculateTotalHours($request->bedtime, $request->wake_time);

            $record->sleep_date = $request->sleep_date;
            $record->bedtime = $request->bedtime;
            $record->wake_time = $request->wake_time;
            $record->total_hours = $totalHours;
            $record->interruptions = $request->interruptions;
            $record->quality = $request->quality;
            $record->felt_rested = $request->has('felt_rested') ? 1 : 0;
            $record->notes = $request->notes;
            $record->save();

            Session::flash('message', ['content' => 'Registro actualizado con éxito', 'type' => 'success']);
            return redirect()->route('sleep.index', ['date' => $request->sleep_date]);
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $record = SleepRecord::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();

            if (empty($record)) {
                Session::flash('message', ['content' => "El registro no existe o no tienes permiso para eliminarlo.", 'type' => 'error']);
                return redirect()->back();
            }

            $record->delete();

            Session::flash('message', ['content' => 'Registro eliminado con éxito', 'type' => 'success']);
            return redirect()->back();
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back();
        }
    }

    public function history(Request $request)
    {
        $period = $request->period ?? 'month'; // week, month, 3months

        $startDate = match($period) {
            'week' => Carbon::today()->subWeek(),
            '3months' => Carbon::today()->subMonths(3),
            default => Carbon::today()->subMonth()
        };

        $records = SleepRecord::where('user_id', Auth::id())
            ->where('sleep_date', '>=', $startDate)
            ->orderBy('sleep_date', 'desc')
            ->paginate(30);

        $goal = SleepGoal::getOrCreateForUser(Auth::id());

        // Estadísticas del período
        $allRecords = SleepRecord::where('user_id', Auth::id())
            ->where('sleep_date', '>=', $startDate)
            ->get();

        $stats = [
            'avg_hours' => $allRecords->avg('total_hours'),
            'min_hours' => $allRecords->min('total_hours'),
            'max_hours' => $allRecords->max('total_hours'),
            'avg_interruptions' => $allRecords->avg('interruptions'),
            'total_nights' => $allRecords->count(),
            'nights_met_goal' => $allRecords->where('total_hours', '>=', $goal->target_hours - 0.5)->count(),
            'felt_rested_count' => $allRecords->where('felt_rested', true)->count(),
        ];

        return view('sleep/history', [
            'records' => $records,
            'stats' => $stats,
            'period' => $period,
            'startDate' => $startDate,
            'goal' => $goal
        ]);
    }

    public function statistics(Request $request)
    {
        $days = $request->days ?? 30;
        $startDate = Carbon::today()->subDays($days - 1);
        $endDate = Carbon::today();

        $records = SleepRecord::where('user_id', Auth::id())
            ->whereBetween('sleep_date', [$startDate, $endDate])
            ->orderBy('sleep_date', 'asc')
            ->get();

        $goal = SleepGoal::getOrCreateForUser(Auth::id());

        // Estadísticas generales
        $stats = [
            'avg_hours' => $records->avg('total_hours'),
            'total_nights' => $records->count(),
            'avg_interruptions' => $records->avg('interruptions'),
            'nights_met_goal' => $records->where('total_hours', '>=', $goal->target_hours - 0.5)->count(),
            'best_sleep' => $records->max('total_hours'),
            'worst_sleep' => $records->min('total_hours'),
            'felt_rested_percent' => $records->count() > 0 ? ($records->where('felt_rested', true)->count() / $records->count()) * 100 : 0,
        ];

        // Estadísticas de calidad
        $qualityStats = SleepRecord::getQualityStats(Auth::id(), $days);

        return view('sleep/statistics', [
            'records' => $records,
            'goal' => $goal,
            'stats' => $stats,
            'qualityStats' => $qualityStats,
            'days' => $days,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function goals()
    {
        $goal = SleepGoal::getOrCreateForUser(Auth::id());
        return view('sleep/goals', ['goal' => $goal]);
    }

    public function updateGoals(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target_hours' => 'required|numeric|min:4|max:12',
            'target_bedtime' => 'nullable|date_format:H:i',
            'target_wake_time' => 'nullable|date_format:H:i',
            'max_interruptions' => 'required|integer|min:0|max:10'
        ], [
            'target_hours.required' => 'La meta de horas es obligatoria.',
            'target_hours.min' => 'La meta debe ser al menos 4 horas.',
            'target_hours.max' => 'La meta no puede exceder 12 horas.',
            'max_interruptions.required' => 'El número máximo de interrupciones es obligatorio.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $goal = SleepGoal::getOrCreateForUser(Auth::id());

            $goal->target_hours = $request->target_hours;
            $goal->target_bedtime = $request->target_bedtime;
            $goal->target_wake_time = $request->target_wake_time;
            $goal->max_interruptions = $request->max_interruptions;
            $goal->save();

            Session::flash('message', ['content' => 'Metas de sueño actualizadas con éxito', 'type' => 'success']);
            return redirect()->route('sleep.index');
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back()->withInput();
        }
    }
}
