@extends('layouts.app')

@section('content')

<div class="container my-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Listado de calificaciones</h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCalificacionModal">
            Crear calificación
        </button>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Alumno</th>
                <th>Materia</th>
                <th>Calificación</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($calificaciones as $calificacion)
                <tr>
                    <td>{{ $calificacion->alumno->name ?? 'N/A' }}</td>
                    <td>{{ $calificacion->materia->nombre ?? 'N/A' }}</td>
                    <td>{{ $calificacion->calificacion }}</td>
                    <td>{{ $calificacion->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('edit.calificaciones', $calificacion->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('delete.calificaciones', $calificacion->id) }}" method="post" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar esta calificación?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay calificaciones registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $calificaciones->links() }}
    </div>
</div>

<div class="modal fade" id="createCalificacionModal" tabindex="-1" aria-labelledby="createCalificacionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('create.calificaciones') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createCalificacionModalLabel">Crear calificación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    @if($errors->has('alumno_id') || $errors->has('materia_id') || $errors->has('calificacion'))
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group mb-3">
                        <label for="alumno_id">Alumno</label>
                        <select name="alumno_id" id="alumno_id" class="form-control" required>
                            <option value="">Seleccione un alumno</option>
                            @foreach ($alumnos as $alumno)
                                <option value="{{ $alumno->id }}" @selected(old('alumno_id') == $alumno->id)>
                                    {{ $alumno->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="materia_id">Materia</label>
                        <select name="materia_id" id="materia_id" class="form-control" required>
                            <option value="">Seleccione una materia</option>
                            @foreach ($materias as $materia)
                                <option value="{{ $materia->id }}" @selected(old('materia_id') == $materia->id)>
                                    {{ $materia->nombre ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="calificacion">Calificación</label>
                        <input type="number" name="calificacion" id="calificacion" class="form-control" min="0" max="100" step="0.01" required value="{{ old('calificacion') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear calificación</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->has('alumno_id') || $errors->has('materia_id') || $errors->has('calificacion'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        bootstrap.Modal.getOrCreateInstance(document.getElementById('createCalificacionModal')).show();
    });
</script>
@endif

@endsection