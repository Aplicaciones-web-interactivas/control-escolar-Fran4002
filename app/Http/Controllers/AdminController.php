<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Horario;
use App\Models\Grupo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function indexAdmin() {
        abort_if(auth()->user()->role !== 'admin', 403);
        return view('admin.dashboard');
    }

    public function indexUsers()
    {
        // Roles that an admin can assign from the users screen.
        $roles = ['admin', 'alumno', 'maestro'];
        $users = User::latest('id')->paginate(10);

        return view('admin.users', compact('users', 'roles'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,alumno,maestro',
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->route('index.users')->with('success', 'Rol de usuario actualizado correctamente');
    }

    public function indexMaterias() {
        $materias = Materia::latest('id')->paginate(10);
        return view('admin.materias')->with('materias', $materias);
    }

    public function createMateria(Request $request) {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'clave' => 'required|string|max:50|unique:materias,clave',
        ]);

        $nuevaMateria = new Materia();
        $nuevaMateria->nombre = $request->nombre;
        $nuevaMateria->clave = $request->clave;
        $nuevaMateria->save();

        return redirect()->route('index.materias')->with('success', 'Materia creada correctamente');
    }

    public function editMateria(Materia $materia) {
        return view('admin.materias_edit')->with('materia', $materia);
    }

    public function updateMateria(Request $request, Materia $materia) {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'clave' => 'required|string|max:50|unique:materias,clave,' . $materia->id,
        ]);

        $materia->nombre = $request->nombre;
        $materia->clave = $request->clave;
        $materia->save();

        return redirect()->route('index.materias')->with('success', 'Materia actualizada correctamente');
    }

    public function deleteMateria(Materia $materia) {
        $materia->delete();
        return redirect()->route('index.materias')->with('success', 'Materia eliminada correctamente');
    }

    public function indexHorarios()
    {
        $horarios = Horario::with(['maestro', 'materia'])->latest('id')->paginate(10);
        $maestros = User::where('role', 'maestro')->get();
        $materias = Materia::all();

        return view('admin.horarios', compact('horarios', 'maestros', 'materias'));
    }

    public function createHorario(Request $request)
    {
        $request->validate([
            'maestro_id'   => 'required|exists:users,id',
            'materia_id'   => 'required|exists:materias,id',
            'dias'         => 'required|string|max:255',
            'hora_inicio'  => 'required|date_format:H:i',
            'hora_fin'     => 'required|date_format:H:i|after:hora_inicio',
        ]);

        Horario::create($request->only([
            'maestro_id',
            'materia_id',
            'dias',
            'hora_inicio',
            'hora_fin',
        ]));

        return redirect()->route('index.horarios')->with('success', 'Horario creado correctamente');
    }

    public function editHorario(Horario $horario)
    {
        $maestros = User::where('role', 'maestro')->get();
        $materias = Materia::all();

        return view('admin.horarios_edit', compact('horario', 'maestros', 'materias'));
    }

    public function updateHorario(Request $request, Horario $horario)
    {
        $request->validate([
            'maestro_id'   => 'required|exists:users,id',
            'materia_id'   => 'required|exists:materias,id',
            'dias'         => 'required|string|max:255',
            'hora_inicio'  => 'required|date_format:H:i',
            'hora_fin'     => 'required|date_format:H:i|after:hora_inicio',
        ]);

        $horario->update($request->only([
            'maestro_id',
            'materia_id',
            'dias',
            'hora_inicio',
            'hora_fin',
        ]));

        return redirect()->route('index.horarios')->with('success', 'Horario actualizado correctamente');
    }

    public function deleteHorario(Horario $horario)
    {
        $horario->delete();

        return redirect()->route('index.horarios')->with('success', 'Horario eliminado correctamente');
    }

    public function indexGrupos()
    {
        $grupos = Grupo::with(['alumno', 'horario.materia', 'horario.maestro'])->latest('id')->paginate(10);
        $alumnos = User::where('role', 'alumno')->get();
        $horarios = Horario::with(['materia', 'maestro'])->get();

        return view('admin.grupos', compact('grupos', 'alumnos', 'horarios'));
    }

    public function createGrupo(Request $request)
    {
        $request->validate([
            'alumno_id'  => 'required|exists:users,id',
            'horario_id' => 'required|exists:horarios,id',
        ]);

        Grupo::create($request->only([
            'alumno_id',
            'horario_id',
        ]));

        return redirect()->route('index.grupos')->with('success', 'Grupo creado correctamente');
    }

    public function editGrupo(Grupo $grupo)
    {
        $alumnos = User::where('role', 'alumno')->get();
        $horarios = Horario::with(['materia', 'maestro'])->get();
        $grupo->load(['horario.materia', 'horario.maestro']);
        $alumnosInscritos = Grupo::with('alumno')
            ->where('horario_id', $grupo->horario_id)
            ->latest('id')
            ->get();

        return view('admin.grupos_edit', compact('grupo', 'alumnos', 'horarios', 'alumnosInscritos'));
    }

    public function updateGrupo(Request $request, Grupo $grupo)
    {
        $request->validate([
            'alumno_id'  => 'required|exists:users,id',
            'horario_id' => 'required|exists:horarios,id',
        ]);

        $grupo->update($request->only([
            'alumno_id',
            'horario_id',
        ]));

        return redirect()->route('index.grupos')->with('success', 'Grupo actualizado correctamente');
    }

    public function deleteGrupo(Grupo $grupo)
    {
        $grupo->delete();

        return redirect()->route('index.grupos')->with('success', 'Grupo eliminado correctamente');
    }

    public function addAlumnoToGrupo(Request $request, Grupo $grupo)
    {
        $validated = $request->validate([
            'alumno_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', 'alumno');
                }),
            ],
        ]);

        $yaInscrito = Grupo::where('horario_id', $grupo->horario_id)
            ->where('alumno_id', $validated['alumno_id'])
            ->exists();

        if ($yaInscrito) {
            return redirect()
                ->route('edit.grupos', $grupo->id)
                ->withErrors(['alumno_id' => 'El alumno ya está inscrito en este grupo.'])
                ->withInput();
        }

        Grupo::create([
            'alumno_id' => $validated['alumno_id'],
            'horario_id' => $grupo->horario_id,
        ]);

        return redirect()
            ->route('edit.grupos', $grupo->id)
            ->with('success', 'Alumno agregado al grupo correctamente');
    }
}
