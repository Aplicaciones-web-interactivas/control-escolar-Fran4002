@extends('layouts.app')

@section('content')

<div class="container my-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Materias</h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMateriaModal">
            Crear materia
        </button>
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

    <div class="d-flex justify-content-center">
        {{ $materias->links() }}
    </div>
</div>

<div class="modal fade" id="createMateriaModal" tabindex="-1" aria-labelledby="createMateriaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('create.materias') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createMateriaModalLabel">Crear materia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    @if($errors->has('nombre') || $errors->has('clave'))
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group mb-3">
                        <label for="nombre">Nombre de la materia</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required value="{{ old('nombre') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="clave">Código de la materia</label>
                        <input type="text" name="clave" id="clave" class="form-control" required value="{{ old('clave') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear materia</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->has('nombre') || $errors->has('clave'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        bootstrap.Modal.getOrCreateInstance(document.getElementById('createMateriaModal')).show();
    });
</script>
@endif

@endsection