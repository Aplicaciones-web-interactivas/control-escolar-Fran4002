@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Entregas de tarea</h3>
        <a href="{{ route('maestros.tareas.index') }}" class="btn btn-secondary">Volver</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ $tarea->titulo }}</h5>
            <p class="card-text mb-1"><strong>Materia:</strong> {{ $tarea->horario->materia->nombre ?? 'N/A' }}</p>
            <p class="card-text mb-1">
                <strong>Horario:</strong>
                {{ $tarea->horario->dias ?? 'N/A' }}
                {{ $tarea->horario->hora_inicio ?? '' }}-{{ $tarea->horario->hora_fin ?? '' }}
            </p>
            <p class="card-text mb-1"><strong>Fecha límite:</strong> {{ $tarea->fecha_limite ?? 'Sin fecha' }}</p>
            <p class="card-text"><strong>Descripción:</strong> {{ $tarea->descripcion ?: 'Sin descripción' }}</p>
        </div>
    </div>

    @php
        $entregasPorAlumno = $tarea->entregas->keyBy('alumno_id');
    @endphp

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Clave institucional</th>
                    <th>Estado</th>
                    <th>Entregado en</th>
                    <th>Archivo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alumnosInscritos as $inscripcion)
                    @php
                        $alumno = $inscripcion->alumno;
                        $entrega = $alumno ? $entregasPorAlumno->get($alumno->id) : null;
                    @endphp
                    <tr>
                        <td>{{ $alumno->name ?? 'N/A' }}</td>
                        <td>{{ $alumno->clave_institucional ?? 'N/A' }}</td>
                        <td>{{ $entrega ? 'Entregada' : 'Pendiente' }}</td>
                        <td>{{ $entrega?->entregado_en ?? '-' }}</td>
                        <td>
                            @if($entrega)
                                <a href="{{ route('maestros.entregas.download', $entrega->id) }}" class="btn btn-sm btn-success">
                                    Descargar PDF
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay alumnos inscritos en esta clase.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
