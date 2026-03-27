@extends('layouts.app')

@section('content')
<div class="container my-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h3 class="mb-3">Tareas asignadas</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tarea</th>
                <th>Materia</th>
                <th>Maestro</th>
                <th>Fecha límite</th>
                <th>Estado</th>
                <th>Entrega PDF</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tareas as $tarea)
                @php
                    $entrega = $tarea->entregas->first();
                    $esTardia = $entrega && $entrega->entregado_en->gt($tarea->fecha_limite->copy()->endOfDay());
                @endphp
                <tr>
                    <td>
                        <strong>{{ $tarea->titulo }}</strong>
                        @if($tarea->descripcion)
                            <div class="small text-muted mt-1">{{ $tarea->descripcion }}</div>
                        @endif
                    </td>
                    <td>{{ $tarea->horario->materia->nombre ?? 'N/A' }}</td>
                    <td>{{ $tarea->maestro->name ?? 'N/A' }}</td>
                    <td>{{ $tarea->fecha_limite?->format('Y-m-d') }}</td>
                    <td>
                        @if(! $entrega)
                            <span class="badge bg-secondary">Pendiente</span>
                        @elseif($entrega->revisado_en)
                            <span class="badge bg-success">Revisada</span>
                        @elseif($esTardia)
                            <span class="badge bg-warning text-dark">Entregada tarde</span>
                        @else
                            <span class="badge bg-primary">Entregada</span>
                        @endif
                    </td>
                    <td>
                        @if($entrega)
                            <div class="mb-2">
                                <a href="{{ route('entregas.archivo', $entrega->id) }}" class="btn btn-sm btn-outline-secondary">
                                    Ver entrega actual
                                </a>
                                <div class="small text-muted mt-1">
                                    {{ $entrega->archivo_nombre_original }} ({{ $entrega->entregado_en->format('Y-m-d H:i') }})
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('alumnos.tareas.entrega', $tarea->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="archivo" accept="application/pdf,.pdf" class="form-control form-control-sm mb-2" required>
                            <button type="submit" class="btn btn-sm btn-primary">
                                {{ $entrega ? 'Reemplazar PDF' : 'Subir PDF' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No tienes tareas asignadas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $tareas->links() }}
    </div>
</div>
@endsection

