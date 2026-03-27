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

    <div class="card p-3">
        <h3>Editar grupo</h3>
        <form action="{{ route('update.grupos', $grupo->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="alumno_id">Alumno</label>
                <select name="alumno_id" id="alumno_id" class="form-control" required>
                    @foreach ($alumnos as $alumno)
                        <option value="{{ $alumno->id }}" @selected(old('alumno_id', $grupo->alumno_id) == $alumno->id)>
                            {{ $alumno->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="horario_id">Horario</label>
                <select name="horario_id" id="horario_id" class="form-control" required>
                    @foreach ($horarios as $horario)
                        <option value="{{ $horario->id }}" @selected(old('horario_id', $grupo->horario_id) == $horario->id)>
                            {{ $horario->materia->nombre ?? 'Sin materia' }} - {{ $horario->maestro->name ?? 'Sin maestro' }} ({{ $horario->dias }} {{ $horario->hora_inicio }}-{{ $horario->hora_fin }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="{{ route('index.grupos') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <div class="card p-3 mt-4">
        <h4>Alumnos del grupo</h4>
        <p class="mb-3 text-muted">
            {{ $grupo->horario->materia->nombre ?? 'Sin materia' }} -
            {{ $grupo->horario->maestro->name ?? 'Sin maestro' }}
            ({{ $grupo->horario->dias ?? 'Sin días' }} {{ $grupo->horario->hora_inicio ?? '' }}-{{ $grupo->horario->hora_fin ?? '' }})
        </p>

        <form action="{{ route('store.grupos.alumnos', $grupo->id) }}" method="post" class="row g-3 align-items-end">
            @csrf
            <div class="col-md-4">
                <label for="alumno_id_inscribir" class="form-label">ID del alumno</label>
                <input
                    type="number"
                    name="alumno_id"
                    id="alumno_id_inscribir"
                    class="form-control"
                    required
                    min="1"
                    value="{{ old('alumno_id') }}"
                >
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success">Agregar alumno</button>
            </div>
        </form>

        <div class="table-responsive mt-3">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Clave institucional</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alumnosInscritos as $inscripcion)
                        <tr>
                            <td>{{ $inscripcion->alumno->id ?? 'N/A' }}</td>
                            <td>{{ $inscripcion->alumno->name ?? 'N/A' }}</td>
                            <td>{{ $inscripcion->alumno->clave_institucional ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No hay alumnos inscritos en este grupo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

