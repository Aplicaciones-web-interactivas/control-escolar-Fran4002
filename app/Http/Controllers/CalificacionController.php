<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\Materia;
use App\Models\User;
use Illuminate\Http\Request;

class CalificacionController extends Controller
{
    public function index()
    {
        $calificaciones = Calificacion::with(['alumno', 'materia'])->get();
        $alumnos = User::where('role', 'alumno')->get();
        $materias = Materia::all();

        return view('admin.calificaciones', compact('calificaciones', 'alumnos', 'materias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alumno_id'     => 'required|exists:users,id',
            'materia_id'    => 'required|exists:materias,id',
            'calificacion'  => 'required|numeric|min:0|max:100',
        ]);

        Calificacion::create($validated);

        return redirect()->route('index.calificaciones')->with('success', 'Calificación creada correctamente');
    }

    public function edit(Calificacion $calificacion)
    {
        $alumnos = User::where('role', 'alumno')->get();
        $materias = Materia::all();

        return view('admin.calificaciones_edit', compact('calificacion', 'alumnos', 'materias'));
    }

    public function update(Request $request, Calificacion $calificacion)
    {
        $validated = $request->validate([
            'alumno_id'     => 'required|exists:users,id',
            'materia_id'    => 'required|exists:materias,id',
            'calificacion'  => 'required|numeric|min:0|max:100',
        ]);

        $calificacion->update($validated);

        return redirect()->route('index.calificaciones')->with('success', 'Calificación actualizada correctamente');
    }

    public function destroy(Calificacion $calificacion)
    {
        $calificacion->delete();

        return redirect()->route('index.calificaciones')->with('success', 'Calificación eliminada correctamente');
    }

    // API
    public function apiIndex()
    {
        return response()->json(Calificacion::with(['alumno', 'materia'])->get());
    }

    public function apiShow(Calificacion $calificacion)
    {
        return response()->json($calificacion->load(['alumno', 'materia']));
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'alumno_id'     => 'required|exists:users,id',
            'materia_id'    => 'required|exists:materias,id',
            'calificacion'  => 'required|numeric|min:0|max:100',
        ]);

        $created = Calificacion::create($validated);

        return response()->json($created->load(['alumno', 'materia']), 201);
    }

    public function apiUpdate(Request $request, Calificacion $calificacion)
    {
        $validated = $request->validate([
            'alumno_id'     => 'required|exists:users,id',
            'materia_id'    => 'required|exists:materias,id',
            'calificacion'  => 'required|numeric|min:0|max:100',
        ]);

        $calificacion->update($validated);

        return response()->json($calificacion->load(['alumno', 'materia']), 200);
    }

    public function apiDestroy(Calificacion $calificacion)
    {
        $calificacion->delete();

        return response()->json(['message' => 'Calificación eliminada correctamente'], 200);
    }
}
