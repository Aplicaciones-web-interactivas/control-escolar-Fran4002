@extends('layouts.app')

@section('content')

<div class="container my-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-3 mb-4">
        <h3>Crear horario</h3>
        <form action="{{ route('create.horarios') }}" method="post">
            @csrf
            <div class="form-group mb-3">
                <label for="maestro_id">Maestro</label>
                <select name="maestro_id" id="maestro_id" class="form-control" required>
                    <option value="">Seleccione un maestro</option>
                    @foreach ($maestros as $maestro)
                        <option value="{{ $maestro->id }}" @selected(old('maestro_id') == $maestro->id)>
                            {{ $maestro->name }}
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
                            {{ $materia->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="dias">Días</label>
                <input type="text" name="dias" id="dias" class="form-control" required value="{{ old('dias') }}" placeholder="Ej: Lunes, Miércoles, Viernes">
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="hora_inicio">Hora de inicio</label>
                    <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required value="{{ old('hora_inicio') }}">
                </div>
                <div class="col">
                    <label for="hora_fin">Hora de fin</label>
                    <input type="time" name="hora_fin" id="hora_fin" class="form-control" required value="{{ old('hora_fin') }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Crear horario</button>
        </form>
    </div>

    <h3>Listado de horarios</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Maestro</th>
                <th>Materia</th>
                <th>Días</th>
                <th>Hora inicio</th>
                <th>Hora fin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($horarios as $horario)
                <tr>
                    <td>{{ $horario->maestro->name ?? 'N/A' }}</td>
                    <td>{{ $horario->materia->nombre ?? 'N/A' }}</td>
                    <td>{{ $horario->dias }}</td>
                    <td>{{ $horario->hora_inicio }}</td>
                    <td>{{ $horario->hora_fin }}</td>
                    <td>
                        <a href="{{ route('edit.horarios', $horario->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('delete.horarios', $horario->id) }}" method="post" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este horario?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay horarios registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

