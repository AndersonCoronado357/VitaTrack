<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Medications;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class NotesController extends Controller
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

        $notes = Note::with('medication')
            ->where('title', 'LIKE', "%$request->filter%")
            ->paginate($request->records_per_page);

        return view('notes/index', ['notes' => $notes, 'data' => $request]);
    }

    public function create()
    {
        $medications = Medications::all();
        return view('notes/create', ['medications' => $medications]);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'title' => 'required|max:64',
            'content' => 'required',
            'medication_id' => 'required|exists:medications,id',
        ], [
            'title.required' => 'El título es requerido.',
            'title.max' => 'El título no puede ser mayor a :max carácteres.',
            'content.required' => 'El contenido es requerido.',
            'medication_id.required' => 'El medicamento es requerido.',
            'medication_id.exists' => 'El id dado para el medicamento no existe.',
        ])->validate();

        try {
            $note = new Note();
            $note->title = $request->title;
            $note->content = $request->content;
            $note->medication_id = $request->medication_id;
            $note->save();

            Session::flash('message', ['content' => 'Nota creada con éxito', 'type' => 'success']);
            return redirect()->action([NotesController::class, 'index']);
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error al crear la nota.', 'type' => 'error']);
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $note = Note::find($id);

        if (empty($note)) {
            Session::flash('message', ['content' => "La nota con id: '$id' no existe.", 'type' => 'error']);
            return redirect()->back();
        }

        $medications = Medications::all();

        return view('notes/edit', [
            'note' => $note,
            'medications' => $medications
        ]);
    }

    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'note_id' => 'required|exists:notes,id',
            'title' => 'required|max:64',
            'content' => 'required',
            'medication_id' => 'required|exists:medications,id',
        ], [
            'note_id.required' => 'El ID de la nota es requerido.',
            'note_id.exists' => 'La nota indicada no existe.',
            'title.required' => 'El título es requerido.',
            'title.max' => 'El título no puede ser mayor a :max carácteres.',
            'content.required' => 'El contenido es requerido.',
            'medication_id.required' => 'El medicamento es requerido.',
            'medication_id.exists' => 'El id dado para el medicamento no existe.',
        ])->validate();

        try {
            $note = Note::find($request->note_id);
            $note->title = $request->title;
            $note->content = $request->content;
            $note->medication_id = $request->medication_id;
            $note->save();

            Session::flash('message', ['content' => 'Nota actualizada con éxito', 'type' => 'success']);
            return redirect()->action([NotesController::class, 'index']);
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error al actualizar la nota.', 'type' => 'error']);
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $note = Note::find($id);

            if (empty($note)) {
                Session::flash('message', ['content' => "La nota con id: '$id' no existe.", 'type' => 'error']);
                return redirect()->back();
            }

            $note->delete();

            Session::flash('message', ['content' => 'Nota eliminada con éxito', 'type' => 'success']);
            return redirect()->action([NotesController::class, 'index']);
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error al eliminar la nota.', 'type' => 'error']);
            return redirect()->back();
        }
    }
}
