@extends('layouts.app')

@section('content')
<div class="container my-4">
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
</div>
@endsection

