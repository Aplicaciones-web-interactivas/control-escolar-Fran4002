@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Editar tarea</h3>
        <a href="{{ route('maestros.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('update.tareas', $tarea->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="horario_id" class="form-label">Horario</label>
                    <select name="horario_id" id="horario_id" class="form-control" required>
                        <option value="">Selecciona un horario</option>
                        @foreach($horarios as $horario)
                            <option value="{{ $horario->id }}" @selected(old('horario_id', $tarea->horario_id) == $horario->id)>
                                {{ $horario->materia->nombre ?? 'Materia' }} - {{ $horario->dias }} {{ $horario->hora_inicio }}-{{ $horario->hora_fin }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" maxlength="255" value="{{ old('titulo', $tarea->titulo) }}" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $tarea->descripcion) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="fecha_limite" class="form-label">Fecha límite</label>
                    <input type="date" class="form-control" id="fecha_limite" name="fecha_limite" value="{{ old('fecha_limite', optional($tarea->fecha_limite)->format('Y-m-d')) }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </form>
        </div>
    </div>
</div>
@endsection
