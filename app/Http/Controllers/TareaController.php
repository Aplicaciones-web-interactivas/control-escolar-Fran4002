<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Horario;
use App\Models\Tarea;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    public function maestroIndex()
    {
        $user = auth()->user();
        abort_if($user->role !== 'maestro', 403);

        $tareas = Tarea::with(['horario.materia'])
            ->where('maestro_id', $user->id)
            ->latest('id')
            ->paginate(10);

        return view('maestros.tareas_index', compact('tareas'));
    }

    public function maestroCreate()
    {
        $user = auth()->user();
        abort_if($user->role !== 'maestro', 403);

        $horarios = Horario::with('materia')
            ->where('maestro_id', $user->id)
            ->latest('id')
            ->get();

        return view('maestros.tareas_create', compact('horarios'));
    }

    public function maestroStore(Request $request)
    {
        $user = auth()->user();
        abort_if($user->role !== 'maestro', 403);

        $validated = $request->validate([
            'horario_id' => 'required|exists:horarios,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_limite' => 'nullable|date',
        ]);

        $horarioEsDelMaestro = Horario::where('id', $validated['horario_id'])
            ->where('maestro_id', $user->id)
            ->exists();

        abort_unless($horarioEsDelMaestro, 403);

        Tarea::create([
            'horario_id' => $validated['horario_id'],
            'maestro_id' => $user->id,
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'] ?? null,
            'fecha_limite' => $validated['fecha_limite'] ?? null,
        ]);

        return redirect()
            ->route('maestros.tareas.index')
            ->with('success', 'Tarea creada correctamente');
    }

    public function maestroShow(Tarea $tarea)
    {
        $user = auth()->user();
        abort_if($user->role !== 'maestro', 403);
        abort_unless((int) $tarea->maestro_id === (int) $user->id, 403);

        $tarea->load([
            'horario.materia',
            'entregas.alumno',
        ]);

        $alumnosInscritos = Grupo::with('alumno')
            ->where('horario_id', $tarea->horario_id)
            ->latest('id')
            ->get();

        return view('maestros.tareas_show', compact('tarea', 'alumnosInscritos'));
    }

    public function alumnoIndex()
    {
        $user = auth()->user();
        abort_if($user->role !== 'alumno', 403);

        $horarioIds = Grupo::where('alumno_id', $user->id)
            ->pluck('horario_id');

        $tareas = Tarea::with([
            'horario.materia',
            'maestro',
            'entregas' => function ($query) use ($user) {
                $query->where('alumno_id', $user->id);
            },
        ])
            ->whereIn('horario_id', $horarioIds)
            ->latest('id')
            ->paginate(10);

        return view('alumnos.tareas_index', compact('tareas'));
    }
}
