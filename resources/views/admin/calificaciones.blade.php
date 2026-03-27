@extends('layouts.app')

@section('content')

<div class="container my-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-3 mb-4">
        <h3>Crear calificación</h3>
        <form action="{{ route('create.calificaciones') }}" method="post">
            @csrf

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

            <button type="submit" class="btn btn-primary">Crear calificación</button>
        </form>
    </div>

    <h3>Listado de calificaciones</h3>
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
</div>

@endsection