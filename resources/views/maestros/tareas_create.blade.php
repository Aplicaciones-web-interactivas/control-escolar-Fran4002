@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Crear tarea</h3>
        <a href="{{ route('maestros.tareas.index') }}" class="btn btn-secondary">Volver</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('maestros.tareas.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="horario_id" class="form-label">Clase (horario)</label>
                    <select name="horario_id" id="horario_id" class="form-control" required>
                        <option value="">Seleccione una clase</option>
                        @foreach($horarios as $horario)
                            <option value="{{ $horario->id }}" @selected(old('horario_id') == $horario->id)>
                                {{ $horario->materia->nombre ?? 'Sin materia' }} ({{ $horario->dias }} {{ $horario->hora_inicio }}-{{ $horario->hora_fin }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo') }}" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="4">{{ old('descripcion') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="fecha_limite" class="form-label">Fecha límite (opcional)</label>
                    <input type="date" name="fecha_limite" id="fecha_limite" class="form-control" value="{{ old('fecha_limite') }}">
                </div>

                <button type="submit" class="btn btn-primary">Guardar tarea</button>
            </form>
        </div>
    </div>
</div>
@endsection
