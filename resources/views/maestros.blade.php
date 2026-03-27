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

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Mis tareas</h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearTareaModal">
            Crear tarea
        </button>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Título</th>
                <th>Materia</th>
                <th>Fecha límite</th>
                <th>Entregas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tareas as $tarea)
                <tr>
                    <td>{{ $tarea->titulo }}</td>
                    <td>{{ $tarea->horario->materia->nombre ?? 'N/A' }}</td>
                    <td>{{ $tarea->fecha_limite?->format('Y-m-d') }}</td>
                    <td>{{ $tarea->entregas_count }}</td>
                    <td class="d-flex gap-1 flex-wrap">
                        <a href="{{ route('maestros.tareas.entregas', $tarea->id) }}" class="btn btn-sm btn-info">
                            Ver entregas
                        </a>
                        <a href="{{ route('edit.tareas', $tarea->id) }}" class="btn btn-sm btn-warning">
                            Editar
                        </a>
                        <form action="{{ route('delete.tareas', $tarea->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar tarea?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay tareas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $tareas->links() }}
    </div>
</div>

<div class="modal fade" id="crearTareaModal" tabindex="-1" aria-labelledby="crearTareaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('create.tareas') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="crearTareaModalLabel">Nueva tarea</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="horario_id" class="form-label">Horario</label>
                        <select name="horario_id" id="horario_id" class="form-control" required>
                            <option value="">Selecciona un horario</option>
                            @foreach($horarios as $horario)
                                <option value="{{ $horario->id }}" @selected(old('horario_id') == $horario->id)>
                                    {{ $horario->materia->nombre ?? 'Materia' }} - {{ $horario->dias }} {{ $horario->hora_inicio }}-{{ $horario->hora_fin }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" maxlength="255" value="{{ old('titulo') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_limite" class="form-label">Fecha límite</label>
                        <input type="date" class="form-control" id="fecha_limite" name="fecha_limite" value="{{ old('fecha_limite') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('crearTareaModal')).show();
        });
    </script>
@endif
@endsection

