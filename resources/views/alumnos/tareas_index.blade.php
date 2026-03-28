@extends('layouts.app')

@section('content')
<div class="container my-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h3 class="mb-3">Mis tareas</h3>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tarea</th>
                    <th>Materia</th>
                    <th>Maestro</th>
                    <th>Fecha límite</th>
                    <th>Mi entrega</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tareas as $tarea)
                    @php
                        $miEntrega = $tarea->entregas->first();
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $tarea->titulo }}</strong><br>
                            <small>{{ $tarea->descripcion }}</small>
                        </td>
                        <td>{{ $tarea->horario->materia->nombre ?? 'N/A' }}</td>
                        <td>{{ $tarea->maestro->name ?? 'N/A' }}</td>
                        <td>{{ $tarea->fecha_limite ?? 'Sin fecha' }}</td>
                        <td>
                            @if($miEntrega)
                                <div class="mb-2">
                                    <span class="badge bg-success">Entregada</span>
                                    <small class="d-block">{{ $miEntrega->entregado_en }}</small>
                                </div>
                                <a href="{{ route('alumnos.tareas.entrega.download', $tarea->id) }}" class="btn btn-sm btn-outline-success mb-2">
                                    Descargar mi PDF
                                </a>
                            @else
                                <span class="badge bg-secondary mb-2">Pendiente</span>
                            @endif

                            <form action="{{ route('alumnos.tareas.entrega.store', $tarea->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="archivo" class="form-control form-control-sm mb-2" accept="application/pdf,.pdf" required>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    {{ $miEntrega ? 'Reemplazar entrega' : 'Subir PDF' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No tienes tareas asignadas.</td>
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
