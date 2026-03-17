@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="card p-3">
        <h3>Editar horario</h3>
        <form action="{{ route('update.horarios', $horario->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="maestro_id">Maestro</label>
                <select name="maestro_id" id="maestro_id" class="form-control" required>
                    @foreach ($maestros as $maestro)
                        <option value="{{ $maestro->id }}" @selected(old('maestro_id', $horario->maestro_id) == $maestro->id)>
                            {{ $maestro->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="materia_id">Materia</label>
                <select name="materia_id" id="materia_id" class="form-control" required>
                    @foreach ($materias as $materia)
                        <option value="{{ $materia->id }}" @selected(old('materia_id', $horario->materia_id) == $materia->id)>
                            {{ $materia->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="dias">Días</label>
                <input type="text" name="dias" id="dias" class="form-control" required value="{{ old('dias', $horario->dias) }}">
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="hora_inicio">Hora de inicio</label>
                    <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required value="{{ old('hora_inicio', $horario->hora_inicio) }}">
                </div>
                <div class="col">
                    <label for="hora_fin">Hora de fin</label>
                    <input type="time" name="hora_fin" id="hora_fin" class="form-control" required value="{{ old('hora_fin', $horario->hora_fin) }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="{{ route('index.horarios') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection

