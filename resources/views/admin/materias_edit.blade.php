@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="card p-3">
        <h3>Editar materia</h3>
        <form action="{{ route('update.materias', $materia->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="nombre">Nombre de la materia</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required value="{{ old('nombre', $materia->nombre) }}">
            </div>
            <div class="form-group mb-3">
                <label for="clave">Código de la materia</label>
                <input type="text" name="clave" id="clave" class="form-control" required value="{{ old('clave', $materia->clave) }}">
            </div>
            <div>
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Guardar cambios
                </button>
                <a href="{{ route('index.materias') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
