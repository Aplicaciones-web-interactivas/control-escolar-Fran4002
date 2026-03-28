@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Panel de maestro</h3>
            <p class="card-text">
                Administra tus tareas por clase y revisa las entregas en PDF de tus alumnos.
            </p>
            <a href="{{ route('maestros.tareas.index') }}" class="btn btn-primary">
                Ir al módulo de tareas
            </a>
        </div>
    </div>
</div>
@endsection

