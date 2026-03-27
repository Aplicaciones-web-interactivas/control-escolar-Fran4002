@extends('layouts.app')

@section('content')

<div class="container my-4">
    <div class="card p-3">
        <h3>Editar calificación</h3>
        <form action="{{ route('update.calificaciones', $calificacion->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="alumno_id">Alumno</label>
                <select name="alumno_id" id="alumno_id" class="form-control" required>
                    <option value="">Seleccione un alumno</option>
                    @foreach ($alumnos as $alumno)
                        <option value="{{ $alumno->id }}" @selected(old('alumno_id', $calificacion->alumno_id) == $alumno->id)>
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
                        <option value="{{ $materia->id }}" @selected(old('materia_id', $calificacion->materia_id) == $materia->id)>
                            {{ $materia->nombre ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="calificacion">Calificación</label>
                <input type="number" name="calificacion" id="calificacion" class="form-control" min="0" max="100" step="0.01" required value="{{ old('calificacion', $calificacion->calificacion) }}">
            </div>

            <button type="submit" class="btn btn-primary">Actualizar calificación</button>
            <a href="{{ route('index.calificaciones') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

@endsection