@extends('layouts.app')

@section('content')

<div class="container my-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-3">
        <form action="{{ route('create.materias') }}" method="post">
            @csrf
            <div class="form-group mb-3">
                <label for="nombre">Nombre de la materia</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required value="{{ old('nombre') }}">
            </div>
            <div class="form-group mb-3">
                <label for="clave">Código de la materia</label>
                <input type="text" name="clave" id="clave" class="form-control" required value="{{ old('clave') }}">
            </div>
            <div>
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Crear Materia
                </button>
            </div>
        </form>
    </div>
</div>

<div class="container">
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Nombre</th>
                <th>Clave</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($materias as $materia)
                <tr>
                    <td>{{ $materia->nombre }}</td>
                    <td>{{ $materia->clave }}</td>
                    <td>
                        <a href="{{ route('edit.materias', $materia->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('delete.materias', $materia->id) }}" method="post" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar esta materia?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No hay materias registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection