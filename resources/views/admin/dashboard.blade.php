@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h2 class="mb-4">Panel de administración</h2>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Materias</h5>
                    <p class="card-text flex-grow-1">
                        Administra el catálogo de materias disponibles.
                    </p>
                    <a href="{{ route('index.materias') }}" class="btn btn-primary mt-auto">
                        Ver materias
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Horarios</h5>
                    <p class="card-text flex-grow-1">
                        Configura los horarios para maestros y materias.
                    </p>
                    <a href="{{ route('index.horarios') }}" class="btn btn-primary mt-auto">
                        Ver horarios
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Grupos</h5>
                    <p class="card-text flex-grow-1">
                        Asigna alumnos a horarios y organiza los grupos.
                    </p>
                    <a href="{{ route('index.grupos') }}" class="btn btn-primary mt-auto">
                        Ver grupos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

