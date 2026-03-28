@extends('layouts.app')

@section('content')

<div class="container my-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Listado de grupos</h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGrupoModal">
            Crear grupo
        </button>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Maestro</th>
                <th>Materia</th>
                <th>Horario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($grupos as $grupo)
                <tr>
                    <td>{{ $grupo->horario->maestro->name ?? 'N/A' }}</td>
                    <td>{{ $grupo->horario->materia->nombre ?? 'N/A' }}</td>                    
                    <td>{{ $grupo->horario->dias }} {{ $grupo->horario->hora_inicio }}-{{ $grupo->horario->hora_fin }}</td>
                    <td>
                        <a href="{{ route('edit.grupos', $grupo->id) }}" class="btn btn-sm btn-warning">Ver grupo</a>
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

    <div class="d-flex justify-content-center">
        {{ $grupos->links() }}
    </div>
</div>

<div class="modal fade" id="createGrupoModal" tabindex="-1" aria-labelledby="createGrupoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('create.grupos') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createGrupoModalLabel">Crear grupo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    @if($errors->has('alumno_id') || $errors->has('horario_id'))
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear grupo</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->has('alumno_id') || $errors->has('horario_id'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        bootstrap.Modal.getOrCreateInstance(document.getElementById('createGrupoModal')).show();
    });
</script>
@endif

@endsection

