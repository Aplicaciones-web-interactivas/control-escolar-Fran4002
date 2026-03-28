@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Panel de alumno</h3>
            <p class="card-text">
                Revisa tus tareas asignadas y entrega tus archivos en formato PDF.
            </p>
            <a href="{{ route('alumnos.tareas.index') }}" class="btn btn-primary">
                Ver mis tareas
            </a>
        </div>
    </div>
</div>
@endsection

