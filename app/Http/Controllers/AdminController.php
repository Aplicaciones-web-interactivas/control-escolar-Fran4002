<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function indexAdmin() {
        return view('admin.dashboard');
    }

    public function indexMaterias() {
        $materias = Materia::all();
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
}
