<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AppointmentsController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->view ?? 'list'; // list, calendar
        $status = $request->status ?? 'all';

        $query = Appointment::where('user_id', Auth::id())
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $appointments = $query->paginate(15);

        // Próximas citas (siguientes 7 días)
        $upcoming = Appointment::getUpcomingAppointments(Auth::id(), 7);

        // Citas de hoy
        $today = Appointment::getTodayAppointments(Auth::id());

        return view('appointments/index', [
            'appointments' => $appointments,
            'upcoming' => $upcoming,
            'today' => $today,
            'view' => $view,
            'status' => $status
        ]);
    }

    public function calendar(Request $request)
    {
        $year = $request->year ?? Carbon::now()->year;
        $month = $request->month ?? Carbon::now()->month;

        $currentDate = Carbon::create($year, $month, 1);
        $appointments = Appointment::getMonthAppointments(Auth::id(), $year, $month);

        // Agrupar por fecha
        $appointmentsByDate = $appointments->groupBy(function($appointment) {
            return $appointment->appointment_date->format('Y-m-d');
        });

        return view('appointments/calendar', [
            'currentDate' => $currentDate,
            'appointments' => $appointments,
            'appointmentsByDate' => $appointmentsByDate,
            'year' => $year,
            'month' => $month
        ]);
    }

    public function create(Request $request)
    {
        $date = $request->date ?? Carbon::today()->format('Y-m-d');
        $type = $request->type ?? 'medical';

        return view('appointments/create', [
            'date' => $date,
            'type' => $type
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'type' => 'required|in:medical,personal,work,other',
            'location' => 'nullable|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:5|max:480',
            'doctor_name' => 'nullable|string|max:150',
            'specialty' => 'nullable|string|max:100',
            'reminder_enabled' => 'nullable|boolean',
            'reminder_minutes' => 'nullable|integer|min:5|max:10080',
            'notes' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7'
        ], [
            'title.required' => 'El título es obligatorio.',
            'type.required' => 'El tipo de cita es obligatorio.',
            'appointment_date.required' => 'La fecha es obligatoria.',
            'appointment_time.required' => 'La hora es obligatoria.',
            'duration.required' => 'La duración es obligatoria.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $appointment = new Appointment();
            $appointment->user_id = Auth::id();
            $appointment->title = $request->title;
            $appointment->description = $request->description;
            $appointment->type = $request->type;
            $appointment->location = $request->location;
            $appointment->appointment_date = $request->appointment_date;
            $appointment->appointment_time = $request->appointment_time;
            $appointment->duration = $request->duration;
            $appointment->doctor_name = $request->doctor_name;
            $appointment->specialty = $request->specialty;
            $appointment->status = 'scheduled';
            $appointment->reminder_enabled = $request->has('reminder_enabled') ? 1 : 0;
            $appointment->reminder_minutes = $request->reminder_minutes ?? 60;
            $appointment->notes = $request->notes;
            $appointment->color = $request->color ?? '#0d6efd';
            $appointment->save();

            Session::flash('message', ['content' => 'Cita creada con éxito', 'type' => 'success']);
            return redirect()->route('appointments.index');
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back()->withInput();
        }
    }

    public function show($id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (empty($appointment)) {
            Session::flash('message', ['content' => "La cita no existe o no tienes permiso para verla.", 'type' => 'error']);
            return redirect()->back();
        }

        return view('appointments/show', ['appointment' => $appointment]);
    }

    public function edit($id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (empty($appointment)) {
            Session::flash('message', ['content' => "La cita no existe o no tienes permiso para editarla.", 'type' => 'error']);
            return redirect()->back();
        }

        return view('appointments/edit', ['appointment' => $appointment]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:appointments,id',
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'type' => 'required|in:medical,personal,work,other',
            'location' => 'nullable|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:5|max:480',
            'doctor_name' => 'nullable|string|max:150',
            'specialty' => 'nullable|string|max:100',
            'status' => 'required|in:scheduled,completed,cancelled,rescheduled',
            'reminder_enabled' => 'nullable|boolean',
            'reminder_minutes' => 'nullable|integer|min:5|max:10080',
            'notes' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $appointment = Appointment::where('id', $request->appointment_id)
                ->where('user_id', Auth::id())
                ->first();

            if (empty($appointment)) {
                Session::flash('message', ['content' => 'No tienes permiso para editar esta cita.', 'type' => 'error']);
                return redirect()->back();
            }

            $appointment->title = $request->title;
            $appointment->description = $request->description;
            $appointment->type = $request->type;
            $appointment->location = $request->location;
            $appointment->appointment_date = $request->appointment_date;
            $appointment->appointment_time = $request->appointment_time;
            $appointment->duration = $request->duration;
            $appointment->doctor_name = $request->doctor_name;
            $appointment->specialty = $request->specialty;
            $appointment->status = $request->status;
            $appointment->reminder_enabled = $request->has('reminder_enabled') ? 1 : 0;
            $appointment->reminder_minutes = $request->reminder_minutes ?? 60;
            $appointment->notes = $request->notes;
            $appointment->color = $request->color ?? '#0d6efd';
            $appointment->save();

            Session::flash('message', ['content' => 'Cita actualizada con éxito', 'type' => 'success']);
            return redirect()->route('appointments.index');
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $appointment = Appointment::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();

            if (empty($appointment)) {
                Session::flash('message', ['content' => "La cita no existe o no tienes permiso para eliminarla.", 'type' => 'error']);
                return redirect()->back();
            }

            $appointment->delete();

            Session::flash('message', ['content' => 'Cita eliminada con éxito', 'type' => 'success']);
            return redirect()->back();
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back();
        }
    }

    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:appointments,id',
            'status' => 'required|in:scheduled,completed,cancelled,rescheduled'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Datos inválidos'], 422);
        }

        try {
            $appointment = Appointment::where('id', $request->appointment_id)
                ->where('user_id', Auth::id())
                ->first();

            if (empty($appointment)) {
                return response()->json(['success' => false, 'message' => 'Cita no encontrada'], 404);
            }

            $appointment->status = $request->status;
            $appointment->save();

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado',
                'status' => $appointment->status_text,
                'color' => $appointment->status_color
            ]);
        } catch (Exception $ex) {
            Log::error($ex);
            return response()->json(['success' => false, 'message' => 'Error al actualizar'], 500);
        }
    }
}
