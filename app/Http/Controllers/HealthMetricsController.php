<?php

namespace App\Http\Controllers;

use App\Models\HealthMetric;
use App\Models\HealthMetricRange;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class HealthMetricsController extends Controller
{
    public function index(Request $request)
    {
        $metricType = $request->metric_type ?? 'all';

        $query = HealthMetric::where('user_id', Auth::id())
            ->orderBy('measured_date', 'desc')
            ->orderBy('measured_time', 'desc');

        if ($metricType !== 'all') {
            $query->where('metric_type', $metricType);
        }

        $metrics = $query->paginate(15);

        // Contar alertas recientes (últimos 7 días)
        $recentAlerts = HealthMetric::where('user_id', Auth::id())
            ->where('measured_date', '>=', Carbon::today()->subDays(7))
            ->where('status', 'alert')
            ->count();

        // Última medición de cada tipo
        $latestMetrics = HealthMetric::where('user_id', Auth::id())
            ->select('metric_type')
            ->selectRaw('MAX(measured_date) as last_date')
            ->groupBy('metric_type')
            ->get();

        return view('health-metrics/index', [
            'metrics' => $metrics,
            'metricType' => $metricType,
            'recentAlerts' => $recentAlerts,
            'latestMetrics' => $latestMetrics
        ]);
    }

    public function create(Request $request)
    {
        $metricType = $request->metric_type ?? 'blood_pressure';
        return view('health-metrics/create', ['metricType' => $metricType]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if (empty($data['measured_time'])) {
            $data['measured_time'] = null;
        }

        $rules = [
            'metric_type' => 'required|string|in:blood_pressure,glucose,weight,heart_rate,temperature,oxygen,cholesterol',
            'value' => 'required|numeric|min:0',
            'value_secondary' => 'nullable|numeric|min:0',
            'measured_date' => 'required|date',
            'measured_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:500',
            'is_fasting' => 'nullable|boolean'
        ];

        $validator = Validator::make($data, $rules, [
            'metric_type.required' => 'El tipo de métrica es obligatorio.',
            'value.required' => 'El valor es obligatorio.',
            'measured_date.required' => 'La fecha es obligatoria.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Determinar unidad según tipo
            $units = [
                'blood_pressure' => 'mmHg',
                'glucose' => 'mg/dL',
                'weight' => 'kg',
                'heart_rate' => 'bpm',
                'temperature' => '°C',
                'oxygen' => '%',
                'cholesterol' => 'mg/dL'
            ];

            $status = HealthMetric::determineStatus(
                $request->metric_type,
                $request->value,
                $request->value_secondary,
                Auth::id()
            );

            $metric = new HealthMetric();
            $metric->user_id = Auth::id();
            $metric->metric_type = $request->metric_type;
            $metric->value = $request->value;
            $metric->value_secondary = $request->value_secondary;
            $metric->unit = $units[$request->metric_type];
            $metric->measured_date = $request->measured_date;
            $metric->measured_time = $data['measured_time'];
            $metric->notes = $request->notes;
            $metric->is_fasting = $request->has('is_fasting') ? 1 : 0;
            $metric->status = $status;
            $metric->save();

            $message = 'Métrica registrada con éxito';
            if ($status === 'alert') {
                $message .= ' - ⚠️ Valor fuera del rango normal';
            } elseif ($status === 'warning') {
                $message .= ' - ⚠️ Valor en zona de atención';
            }

            Session::flash('message', ['content' => $message, 'type' => $status === 'normal' ? 'success' : 'warning']);
            return redirect()->route('health-metrics.index', ['metric_type' => $request->metric_type]);
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $metric = HealthMetric::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (empty($metric)) {
            Session::flash('message', ['content' => "El registro no existe o no tienes permiso para editarlo.", 'type' => 'error']);
            return redirect()->back();
        }

        return view('health-metrics/edit', ['metric' => $metric]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        if (empty($data['measured_time'])) {
            $data['measured_time'] = null;
        }

        $validator = Validator::make($data, [
            'metric_id' => 'required|exists:health_metrics,id',
            'value' => 'required|numeric|min:0',
            'value_secondary' => 'nullable|numeric|min:0',
            'measured_date' => 'required|date',
            'measured_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:500',
            'is_fasting' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $metric = HealthMetric::where('id', $request->metric_id)
                ->where('user_id', Auth::id())
                ->first();

            if (empty($metric)) {
                Session::flash('message', ['content' => 'No tienes permiso para editar este registro.', 'type' => 'error']);
                return redirect()->back();
            }

            $status = HealthMetric::determineStatus(
                $metric->metric_type,
                $request->value,
                $request->value_secondary,
                Auth::id()
            );

            $metric->value = $request->value;
            $metric->value_secondary = $request->value_secondary;
            $metric->measured_date = $request->measured_date;
            $metric->measured_time = $data['measured_time'];
            $metric->notes = $request->notes;
            $metric->is_fasting = $request->has('is_fasting') ? 1 : 0;
            $metric->status = $status;
            $metric->save();

            Session::flash('message', ['content' => 'Registro actualizado con éxito', 'type' => 'success']);
            return redirect()->route('health-metrics.index', ['metric_type' => $metric->metric_type]);
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $metric = HealthMetric::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();

            if (empty($metric)) {
                Session::flash('message', ['content' => "El registro no existe o no tienes permiso para eliminarlo.", 'type' => 'error']);
                return redirect()->back();
            }

            $metric->delete();

            Session::flash('message', ['content' => 'Registro eliminado con éxito', 'type' => 'success']);
            return redirect()->back();
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back();
        }
    }

    public function statistics(Request $request)
    {
        $metricType = $request->metric_type ?? 'blood_pressure';
        $days = $request->days ?? 30;

        $startDate = Carbon::today()->subDays($days - 1);
        $endDate = Carbon::today();

        $metrics = HealthMetric::where('user_id', Auth::id())
            ->where('metric_type', $metricType)
            ->whereBetween('measured_date', [$startDate, $endDate])
            ->orderBy('measured_date', 'asc')
            ->get();

        // Estadísticas
        $stats = [
            'avg' => $metrics->avg('value'),
            'min' => $metrics->min('value'),
            'max' => $metrics->max('value'),
            'count' => $metrics->count(),
            'alerts' => $metrics->where('status', 'alert')->count(),
            'warnings' => $metrics->where('status', 'warning')->count(),
        ];

        $ranges = HealthMetric::getDefaultRanges($metricType);

        // Rangos personalizados si existen
        $customRange = HealthMetricRange::where('user_id', Auth::id())
            ->where('metric_type', $metricType)
            ->first();

        return view('health-metrics/statistics', [
            'metrics' => $metrics,
            'metricType' => $metricType,
            'days' => $days,
            'stats' => $stats,
            'ranges' => $customRange ?? (object)$ranges,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function ranges()
    {
        $ranges = HealthMetricRange::where('user_id', Auth::id())->get()->keyBy('metric_type');
        $metricTypes = ['blood_pressure', 'glucose', 'weight', 'heart_rate', 'temperature', 'oxygen', 'cholesterol'];

        return view('health-metrics/ranges', [
            'ranges' => $ranges,
            'metricTypes' => $metricTypes
        ]);
    }

    public function updateRanges(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'metric_type' => 'required|string',
            'min_normal' => 'required|numeric',
            'max_normal' => 'required|numeric|gte:min_normal',
            'min_warning' => 'required|numeric',
            'max_warning' => 'required|numeric|gte:min_warning',
            'min_normal_secondary' => 'nullable|numeric',
            'max_normal_secondary' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            HealthMetricRange::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'metric_type' => $request->metric_type
                ],
                [
                    'min_normal' => $request->min_normal,
                    'max_normal' => $request->max_normal,
                    'min_warning' => $request->min_warning,
                    'max_warning' => $request->max_warning,
                    'min_normal_secondary' => $request->min_normal_secondary,
                    'max_normal_secondary' => $request->max_normal_secondary,
                ]
            );

            Session::flash('message', ['content' => 'Rangos actualizados con éxito', 'type' => 'success']);
            return redirect()->back();
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back();
        }
    }
}
