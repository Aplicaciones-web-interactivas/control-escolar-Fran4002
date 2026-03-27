@extends('layouts.app')

@section('content')

<div class="container my-4">
    <h2 class="mb-4">Administrar Usuarios</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Clave institucional</th>
                <th>Correo</th>
                <th>Rol actual</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->clave_institucional }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                        <form action="{{ route('update.users.role', $user->id) }}" method="POST" class="d-flex align-items-center gap-2">
                            @csrf
                            @method('PUT')

                            <select name="role" class="form-select form-select-sm" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" @if($user->role === $role) selected @endif>{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>

                            <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay usuarios registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</div>

@endsection