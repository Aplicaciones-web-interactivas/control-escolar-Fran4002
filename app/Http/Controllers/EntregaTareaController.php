<?php

namespace App\Http\Controllers;

use App\Models\EntregaTarea;
use App\Models\Grupo;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EntregaTareaController extends Controller
{
    public function storeOrUpdate(Request $request, Tarea $tarea)
    {
        $user = auth()->user();
        abort_if($user->role !== 'alumno', 403);

        $estaInscrito = Grupo::where('alumno_id', $user->id)
            ->where('horario_id', $tarea->horario_id)
            ->exists();

        abort_unless($estaInscrito, 403);

        $validated = $request->validate([
            'archivo' => 'required|file|mimes:pdf|max:10240',
        ]);

        $archivo = $validated['archivo'];
        $entregaExistente = EntregaTarea::where('tarea_id', $tarea->id)
            ->where('alumno_id', $user->id)
            ->first();

        if ($entregaExistente && Storage::disk('local')->exists($entregaExistente->archivo_path)) {
            Storage::disk('local')->delete($entregaExistente->archivo_path);
        }

        $nombreArchivo = 'alumno_' . $user->id . '_' . time() . '.pdf';
        $archivoPath = $archivo->storeAs(
            'tareas/' . $tarea->id,
            $nombreArchivo,
            'local'
        );

        EntregaTarea::updateOrCreate(
            [
                'tarea_id' => $tarea->id,
                'alumno_id' => $user->id,
            ],
            [
                'archivo_path' => $archivoPath,
                'archivo_nombre_original' => $archivo->getClientOriginalName(),
                'archivo_mime' => $archivo->getClientMimeType(),
                'archivo_size' => $archivo->getSize(),
                'entregado_en' => now(),
            ]
        );

        return redirect()
            ->route('alumnos.tareas.index')
            ->with('success', 'Tarea entregada correctamente');
    }

    public function downloadPropia(Tarea $tarea)
    {
        $user = auth()->user();
        abort_if($user->role !== 'alumno', 403);

        $entrega = EntregaTarea::where('tarea_id', $tarea->id)
            ->where('alumno_id', $user->id)
            ->firstOrFail();

        abort_unless(Storage::disk('local')->exists($entrega->archivo_path), 404);

        return Storage::disk('local')->download(
            $entrega->archivo_path,
            $entrega->archivo_nombre_original
        );
    }

    public function downloadComoMaestro(EntregaTarea $entrega)
    {
        $user = auth()->user();
        abort_if($user->role !== 'maestro', 403);

        $entrega->load('tarea');
        abort_unless((int) $entrega->tarea->maestro_id === (int) $user->id, 403);
        abort_unless(Storage::disk('local')->exists($entrega->archivo_path), 404);

        return Storage::disk('local')->download(
            $entrega->archivo_path,
            $entrega->archivo_nombre_original
        );
    }
}
