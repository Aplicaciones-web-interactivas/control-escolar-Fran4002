<?php

namespace App\Http\Controllers;

use App\Models\EntregaTarea;
use App\Models\Horario;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TareaController extends Controller
{
    public function indexMaestro()
    {
        abort_if(auth()->user()->role !== 'maestro', 403);

        $maestroId = auth()->id();
        $horarios = Horario::with('materia')
            ->where('maestro_id', $maestroId)
            ->latest('id')
            ->get();

        $tareas = Tarea::with(['horario.materia'])
            ->withCount('entregas')
            ->where('maestro_id', $maestroId)
            ->latest('id')
            ->paginate(10);

        return view('maestros', compact('horarios', 'tareas'));
    }

    public function storeTarea(Request $request)
    {
        abort_if(auth()->user()->role !== 'maestro', 403);

        $validated = $request->validate([
            'horario_id' => [
                'required',
                Rule::exists('horarios', 'id')->where(function ($query) {
                    $query->where('maestro_id', auth()->id());
                }),
            ],
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_limite' => 'required|date',
        ]);

        $validated['maestro_id'] = auth()->id();
        Tarea::create($validated);

        return redirect()->route('maestros.index')->with('success', 'Tarea creada correctamente');
    }

    public function editTarea(Tarea $tarea)
    {
        abort_if(auth()->user()->role !== 'maestro', 403);
        abort_if($tarea->maestro_id !== auth()->id(), 403);

        $horarios = Horario::with('materia')
            ->where('maestro_id', auth()->id())
            ->latest('id')
            ->get();

        return view('maestros_tarea_edit', compact('tarea', 'horarios'));
    }

    public function updateTarea(Request $request, Tarea $tarea)
    {
        abort_if(auth()->user()->role !== 'maestro', 403);
        abort_if($tarea->maestro_id !== auth()->id(), 403);

        $validated = $request->validate([
            'horario_id' => [
                'required',
                Rule::exists('horarios', 'id')->where(function ($query) {
                    $query->where('maestro_id', auth()->id());
                }),
            ],
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_limite' => 'required|date',
        ]);

        $tarea->update($validated);

        return redirect()->route('maestros.index')->with('success', 'Tarea actualizada correctamente');
    }

    public function destroyTarea(Tarea $tarea)
    {
        abort_if(auth()->user()->role !== 'maestro', 403);
        abort_if($tarea->maestro_id !== auth()->id(), 403);

        $tarea->delete();

        return redirect()->route('maestros.index')->with('success', 'Tarea eliminada correctamente');
    }

    public function showEntregasMaestro(Tarea $tarea)
    {
        abort_if(auth()->user()->role !== 'maestro', 403);
        abort_if($tarea->maestro_id !== auth()->id(), 403);

        $tarea->load(['horario.materia', 'entregas.alumno']);

        return view('maestros_tarea_entregas', compact('tarea'));
    }

    public function marcarRevisada(Tarea $tarea, EntregaTarea $entrega)
    {
        abort_if(auth()->user()->role !== 'maestro', 403);
        abort_if($tarea->maestro_id !== auth()->id(), 403);
        abort_if($entrega->tarea_id !== $tarea->id, 404);

        $entrega->update([
            'revisado_en' => now(),
        ]);

        return redirect()
            ->route('maestros.tareas.entregas', $tarea->id)
            ->with('success', 'Entrega marcada como revisada');
    }

    public function indexAlumno()
    {
        abort_if(auth()->user()->role !== 'alumno', 403);

        $alumnoId = auth()->id();
        $tareas = Tarea::with([
            'horario.materia',
            'maestro',
            'entregas' => function ($query) use ($alumnoId) {
                $query->where('alumno_id', $alumnoId);
            },
        ])
            ->whereHas('horario.grupos', function ($query) use ($alumnoId) {
                $query->where('alumno_id', $alumnoId);
            })
            ->latest('fecha_limite')
            ->latest('id')
            ->paginate(10);

        return view('alumnos', compact('tareas'));
    }

    public function storeEntrega(Request $request, Tarea $tarea)
    {
        abort_if(auth()->user()->role !== 'alumno', 403);

        $alumnoId = auth()->id();
        $puedeEntregar = $tarea->horario()
            ->whereHas('grupos', function ($query) use ($alumnoId) {
                $query->where('alumno_id', $alumnoId);
            })
            ->exists();

        abort_if(! $puedeEntregar, 403);

        $request->validate([
            'archivo' => 'required|file|mimes:pdf|max:10240',
        ]);

        $archivo = $request->file('archivo');
        $entregaExistente = EntregaTarea::where('tarea_id', $tarea->id)
            ->where('alumno_id', $alumnoId)
            ->first();

        if ($entregaExistente && Storage::disk('local')->exists($entregaExistente->archivo_path)) {
            Storage::disk('local')->delete($entregaExistente->archivo_path);
        }

        $archivoPath = $archivo->storeAs(
            "tareas/tarea_{$tarea->id}/alumno_{$alumnoId}",
            Str::uuid() . '.pdf',
            'local'
        );

        EntregaTarea::updateOrCreate(
            [
                'tarea_id' => $tarea->id,
                'alumno_id' => $alumnoId,
            ],
            [
                'archivo_path' => $archivoPath,
                'archivo_nombre_original' => $archivo->getClientOriginalName(),
                'mime_type' => $archivo->getClientMimeType() ?? 'application/pdf',
                'entregado_en' => now(),
                'revisado_en' => null,
            ]
        );

        $mensaje = $entregaExistente
            ? 'Entrega actualizada correctamente'
            : 'Entrega registrada correctamente';

        return redirect()->route('alumnos.index')->with('success', $mensaje);
    }

    public function downloadEntrega(EntregaTarea $entrega)
    {
        abort_if(! auth()->check(), 403);

        $usuario = auth()->user();
        $entrega->load('tarea');

        $esAlumnoPropietario = $usuario->role === 'alumno' && $entrega->alumno_id === $usuario->id;
        $esMaestroPropietario = $usuario->role === 'maestro' && $entrega->tarea->maestro_id === $usuario->id;

        abort_if(! $esAlumnoPropietario && ! $esMaestroPropietario, 403);
        abort_if(! Storage::disk('local')->exists($entrega->archivo_path), 404);

        return Storage::disk('local')->download(
            $entrega->archivo_path,
            $entrega->archivo_nombre_original,
            ['Content-Type' => 'application/pdf']
        );
    }
}
