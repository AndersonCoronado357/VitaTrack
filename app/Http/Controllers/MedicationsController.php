<?php

namespace App\Http\Controllers;

use App\Models\Medications;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MedicationsController extends Controller
{

    public function index() {

        $medications = Medications::all();

        return view('medications/index', ['medications' => $medications]);
    }

    public function create() {

        return view('medications/create');
    }

    public function store(Request $request) {

        Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'dosage' => 'required|string|max:150',
            'frequency' => 'required|string|max:150',
            'time' => 'required|string|max:150',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'administration_route' => 'required|string|max:100',
            'reminder' => 'boolean',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|in:active,finished,suspended'
        ], [
            'name.required' => 'El nombre del medicamento es obligatorio.',
            'dosage.required' => 'La dosis es obligatoria.',
            'frequency.required' => 'La frecuencia es obligatoria.',
            'time.required' => 'El horario es obligatorio.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'end_date.after_or_equal' => 'La fecha de finalización debe ser posterior o igual a la de inicio.',
            'administration_route.required' => 'La vía de administración es obligatoria.'
        ])->validate();

        try {
            $medication = new Medications();
            $medication->name = $request->name;
            $medication->dosage = $request->dosage;
            $medication->frequency = $request->frequency;
            $medication->time = $request->time;
            $medication->start_date = $request->start_date;
            $medication->end_date = $request->end_date;
            $medication->administration_route = $request->administration_route;
            $medication->reminder = $request->reminder ?? true;
            $medication->notes = $request->notes;
            $medication->status = $request->status ?? 'active';
            $medication->save();

            Session::flash('message', ['content' => 'Medicamento agregado con éxito', 'type' => 'success']);
            return redirect()->route('medications.index');
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back();
        }
    }

    public function edit($id) {

        $medication = Medications::find($id);

        if (empty($medication)) {
            Session::flash('message', ['content' => "El medicamento con id: '$id' no existe.", 'type' => 'error']);
            return redirect()->back();
        }

        return view('medications/edit', ['medication' => $medication]);
    }

    public function update(Request $request) {

        Validator::make($request->all(), [
            'medication_id' => 'required|exists:medications,id',
            'name' => 'required|string|max:150',
            'dosage' => 'required|string|max:150',
            'frequency' => 'required|string|max:150',
            'time' => 'required|string|max:150',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'administration_route' => 'required|string|max:100',
            'reminder' => 'boolean',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|in:active,finished,suspended'
        ], [
            'medication_id.required' => 'El ID del medicamento es obligatorio.',
            'medication_id.exists' => 'El medicamento indicado no existe en la base de datos.',
            'name.required' => 'El nombre del medicamento es obligatorio.',
            'dosage.required' => 'La dosis es obligatoria.',
            'frequency.required' => 'La frecuencia es obligatoria.',
            'time.required' => 'El horario es obligatorio.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'end_date.after_or_equal' => 'La fecha de finalización debe ser posterior o igual a la de inicio.',
            'administration_route.required' => 'La vía de administración es obligatoria.'
        ])->validate();

        try {
            $medication = Medications::find($request->medication_id);
            $medication->name = $request->name;
            $medication->dosage = $request->dosage;
            $medication->frequency = $request->frequency;
            $medication->time = $request->time;
            $medication->start_date = $request->start_date;
            $medication->end_date = $request->end_date;
            $medication->administration_route = $request->administration_route;
            $medication->reminder = $request->reminder ?? true;
            $medication->notes = $request->notes;
            $medication->status = $request->status ?? 'active';
            $medication->save();

            Session::flash('message', ['content' => 'Medicamento actualizado con éxito', 'type' => 'success']);
            return redirect()->route('medications.index');
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back();
        }
    }

    public function delete($id) {

        try {
            $medication = Medications::find($id);

            if (empty($medication)) {
                Session::flash('message', ['content' => "El medicamento con id: '$id' no existe.", 'type' => 'error']);
                return redirect()->back();
            }

            $medication->delete();

            Session::flash('message', ['content' => 'Medicamento eliminado con éxito', 'type' => 'success']);
            return redirect()->route('medications.index');
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back();
        }
    }
}
