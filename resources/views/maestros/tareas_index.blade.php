@extends('layouts.app')

@section('content')
<div class="container my-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Mis tareas</h3>
        <a href="{{ route('maestros.tareas.create') }}" class="btn btn-primary">
            Crear tarea
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Materia</th>
                    <th>Horario</th>
                    <th>Fecha límite</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tareas as $tarea)
                    <tr>
                        <td>{{ $tarea->titulo }}</td>
                        <td>{{ $tarea->horario->materia->nombre ?? 'N/A' }}</td>
                        <td>
                            {{ $tarea->horario->dias ?? 'N/A' }}
                            {{ $tarea->horario->hora_inicio ?? '' }}-{{ $tarea->horario->hora_fin ?? '' }}
                        </td>
                        <td>{{ $tarea->fecha_limite ?? 'Sin fecha' }}</td>
                        <td>
                            <a href="{{ route('maestros.tareas.show', $tarea->id) }}" class="btn btn-sm btn-info">
                                Ver entregas
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No has creado tareas todavía.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $tareas->links() }}
    </div>
</div>
@endsection
