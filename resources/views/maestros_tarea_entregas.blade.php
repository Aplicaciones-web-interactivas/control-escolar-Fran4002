@extends('layouts.app')

@section('content')
<div class="container my-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h3 class="mb-1">Entregas de tarea</h3>
            <div class="text-muted">
                <strong>{{ $tarea->titulo }}</strong> -
                {{ $tarea->horario->materia->nombre ?? 'N/A' }} -
                Límite: {{ $tarea->fecha_limite?->format('Y-m-d') }}
            </div>
        </div>
        <a href="{{ route('maestros.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Alumno</th>
                <th>Archivo</th>
                <th>Entregado en</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tarea->entregas as $entrega)
                @php
                    $esTardia = $entrega->entregado_en->gt($tarea->fecha_limite->copy()->endOfDay());
                @endphp
                <tr>
                    <td>{{ $entrega->alumno->name ?? 'N/A' }}</td>
                    <td>{{ $entrega->archivo_nombre_original }}</td>
                    <td>{{ $entrega->entregado_en->format('Y-m-d H:i') }}</td>
                    <td>
                        @if($entrega->revisado_en)
                            <span class="badge bg-success">Revisada</span>
                        @elseif($esTardia)
                            <span class="badge bg-warning text-dark">Entregada tarde</span>
                        @else
                            <span class="badge bg-primary">Entregada</span>
                        @endif
                    </td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('entregas.archivo', $entrega->id) }}" class="btn btn-sm btn-info">
                            Descargar PDF
                        </a>
                        @if(! $entrega->revisado_en)
                            <form action="{{ route('maestros.tareas.entregas.revisar', [$tarea->id, $entrega->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    Marcar revisada
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Aún no hay entregas para esta tarea.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
