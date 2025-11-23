<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\NutritionGoal;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class NutritionController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? Carbon::today()->format('Y-m-d');

        $meals = Meal::where('user_id', Auth::id())
            ->where('meal_date', $date)
            ->orderBy('meal_time', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $goal = NutritionGoal::getOrCreateForUser(Auth::id());

        $totals = Meal::getMacrosByDate(Auth::id(), $date);

        return view('nutrition/index', [
            'meals' => $meals,
            'goal' => $goal,
            'totals' => $totals,
            'date' => $date
        ]);
    }

    public function create(Request $request)
    {
        $date = $request->date ?? Carbon::today()->format('Y-m-d');
        $mealType = $request->meal_type ?? 'breakfast';

        return view('nutrition/create', [
            'date' => $date,
            'mealType' => $mealType
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if (empty($data['meal_time'])) {
            $data['meal_time'] = null;
        }

        $validator = Validator::make($data, [
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
            'food_name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'calories' => 'required|integer|min:0',
            'proteins' => 'required|numeric|min:0',
            'carbs' => 'required|numeric|min:0',
            'fats' => 'required|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'meal_date' => 'required|date',
            'meal_time' => 'nullable|date_format:H:i',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'meal_type.required' => 'El tipo de comida es obligatorio.',
            'food_name.required' => 'El nombre del alimento es obligatorio.',
            'quantity.required' => 'La cantidad es obligatoria.',
            'calories.required' => 'Las calorías son obligatorias.',
            'meal_date.required' => 'La fecha es obligatoria.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $meal = new Meal();
            $meal->user_id = Auth::id();
            $meal->meal_type = $request->meal_type;
            $meal->food_name = $request->food_name;
            $meal->description = $request->description;
            $meal->quantity = $request->quantity;
            $meal->unit = $request->unit;
            $meal->calories = $request->calories;
            $meal->proteins = $request->proteins;
            $meal->carbs = $request->carbs;
            $meal->fats = $request->fats;
            $meal->fiber = $request->fiber ?? 0;
            $meal->meal_date = $request->meal_date;
            $meal->meal_time = $data['meal_time'];

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('meals', 'public');
                $meal->image_path = $path;
            }

            $meal->save();

            Session::flash('message', ['content' => 'Comida registrada con éxito', 'type' => 'success']);
            return redirect()->route('nutrition.index', ['date' => $request->meal_date]);
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $meal = Meal::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (empty($meal)) {
            Session::flash('message', ['content' => "El registro no existe o no tienes permiso para editarlo.", 'type' => 'error']);
            return redirect()->back();
        }

        return view('nutrition/edit', ['meal' => $meal]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        if (empty($data['meal_time'])) {
            $data['meal_time'] = null;
        }

        $validator = Validator::make($data, [
            'meal_id' => 'required|exists:meals,id',
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
            'food_name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'calories' => 'required|integer|min:0',
            'proteins' => 'required|numeric|min:0',
            'carbs' => 'required|numeric|min:0',
            'fats' => 'required|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'meal_date' => 'required|date',
            'meal_time' => 'nullable|date_format:H:i',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $meal = Meal::where('id', $request->meal_id)
                ->where('user_id', Auth::id())
                ->first();

            if (empty($meal)) {
                Session::flash('message', ['content' => 'No tienes permiso para editar este registro.', 'type' => 'error']);
                return redirect()->back();
            }

            $meal->meal_type = $request->meal_type;
            $meal->food_name = $request->food_name;
            $meal->description = $request->description;
            $meal->quantity = $request->quantity;
            $meal->unit = $request->unit;
            $meal->calories = $request->calories;
            $meal->proteins = $request->proteins;
            $meal->carbs = $request->carbs;
            $meal->fats = $request->fats;
            $meal->fiber = $request->fiber ?? 0;
            $meal->meal_date = $request->meal_date;
            $meal->meal_time = $data['meal_time'];

            if ($request->hasFile('image')) {
                // Eliminar imagen anterior si existe
                if ($meal->image_path) {
                    Storage::disk('public')->delete($meal->image_path);
                }
                $path = $request->file('image')->store('meals', 'public');
                $meal->image_path = $path;
            }

            $meal->save();

            Session::flash('message', ['content' => 'Registro actualizado con éxito', 'type' => 'success']);
            return redirect()->route('nutrition.index', ['date' => $request->meal_date]);
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $meal = Meal::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();

            if (empty($meal)) {
                Session::flash('message', ['content' => "El registro no existe o no tienes permiso para eliminarlo.", 'type' => 'error']);
                return redirect()->back();
            }

            // Eliminar imagen si existe
            if ($meal->image_path) {
                Storage::disk('public')->delete($meal->image_path);
            }

            $meal->delete();

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
        $startDate = $request->start_date ?? Carbon::today()->subDays(30)->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::today()->format('Y-m-d');

        $meals = Meal::where('user_id', Auth::id())
            ->whereBetween('meal_date', [$startDate, $endDate])
            ->orderBy('meal_date', 'desc')
            ->orderBy('meal_time', 'desc')
            ->paginate(20);

        return view('nutrition/history', [
            'meals' => $meals,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function statistics(Request $request)
    {
        $days = $request->days ?? 30;
        $startDate = Carbon::today()->subDays($days - 1);
        $endDate = Carbon::today();

        $goal = NutritionGoal::getOrCreateForUser(Auth::id());

        // Datos diarios para gráficas
        $dailyData = Meal::where('user_id', Auth::id())
            ->whereBetween('meal_date', [$startDate, $endDate])
            ->selectRaw('
                meal_date,
                SUM(calories) as total_calories,
                SUM(proteins) as total_proteins,
                SUM(carbs) as total_carbs,
                SUM(fats) as total_fats
            ')
            ->groupBy('meal_date')
            ->orderBy('meal_date', 'asc')
            ->get();

        // Promedios
        $averages = Meal::where('user_id', Auth::id())
            ->whereBetween('meal_date', [$startDate, $endDate])
            ->selectRaw('
                AVG(calories) as avg_calories,
                AVG(proteins) as avg_proteins,
                AVG(carbs) as avg_carbs,
                AVG(fats) as avg_fats
            ')
            ->first();

        // Distribución por tipo de comida
        $mealTypeDistribution = Meal::where('user_id', Auth::id())
            ->whereBetween('meal_date', [$startDate, $endDate])
            ->selectRaw('meal_type, SUM(calories) as total_calories')
            ->groupBy('meal_type')
            ->get();

        return view('nutrition/statistics', [
            'goal' => $goal,
            'dailyData' => $dailyData,
            'averages' => $averages,
            'mealTypeDistribution' => $mealTypeDistribution,
            'days' => $days,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function goals()
    {
        $goal = NutritionGoal::getOrCreateForUser(Auth::id());
        return view('nutrition/goals', ['goal' => $goal]);
    }

    public function updateGoals(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'daily_calories_goal' => 'required|integer|min:800|max:5000',
            'daily_proteins_goal' => 'required|numeric|min:0|max:500',
            'daily_carbs_goal' => 'required|numeric|min:0|max:1000',
            'daily_fats_goal' => 'required|numeric|min:0|max:500',
            'daily_fiber_goal' => 'nullable|numeric|min:0|max:100',
            'daily_water_goal' => 'nullable|numeric|min:0|max:10000'
        ], [
            'daily_calories_goal.required' => 'La meta de calorías es obligatoria.',
            'daily_calories_goal.min' => 'La meta de calorías debe ser al menos 800.',
            'daily_calories_goal.max' => 'La meta de calorías no puede exceder 5000.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $goal = NutritionGoal::getOrCreateForUser(Auth::id());

            $goal->daily_calories_goal = $request->daily_calories_goal;
            $goal->daily_proteins_goal = $request->daily_proteins_goal;
            $goal->daily_carbs_goal = $request->daily_carbs_goal;
            $goal->daily_fats_goal = $request->daily_fats_goal;
            $goal->daily_fiber_goal = $request->daily_fiber_goal ?? 25;
            $goal->daily_water_goal = $request->daily_water_goal ?? 2000;
            $goal->save();

            Session::flash('message', ['content' => 'Metas nutricionales actualizadas con éxito', 'type' => 'success']);
            return redirect()->route('nutrition.index');
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back()->withInput();
        }
    }
}
