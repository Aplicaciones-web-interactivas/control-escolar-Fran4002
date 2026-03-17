@extends('layouts.app')

@section('content')

<div class="container my-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-3 mb-4">
        <h3>Crear grupo</h3>
        <form action="{{ route('create.grupos') }}" method="post">
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
                <label for="horario_id">Horario</label>
                <select name="horario_id" id="horario_id" class="form-control" required>
                    <option value="">Seleccione un horario</option>
                    @foreach ($horarios as $horario)
                        <option value="{{ $horario->id }}" @selected(old('horario_id') == $horario->id)>
                            {{ $horario->materia->nombre ?? 'Sin materia' }} - {{ $horario->maestro->name ?? 'Sin maestro' }} ({{ $horario->dias }} {{ $horario->hora_inicio }}-{{ $horario->hora_fin }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Crear grupo</button>
        </form>
    </div>

    <h3>Listado de grupos</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Alumno</th>
                <th>Materia</th>
                <th>Maestro</th>
                <th>Horario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($grupos as $grupo)
                <tr>
                    <td>{{ $grupo->alumno->name ?? 'N/A' }}</td>
                    <td>{{ $grupo->horario->materia->nombre ?? 'N/A' }}</td>
                    <td>{{ $grupo->horario->maestro->name ?? 'N/A' }}</td>
                    <td>{{ $grupo->horario->dias }} {{ $grupo->horario->hora_inicio }}-{{ $grupo->horario->hora_fin }}</td>
                    <td>
                        <a href="{{ route('edit.grupos', $grupo->id) }}\" class=\"btn btn-sm btn-warning\">Editar</a>
                        <form action="{{ route('delete.grupos', $grupo->id) }}" method="post" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este grupo?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay grupos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

