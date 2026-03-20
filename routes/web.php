<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalificacionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

route::get('/dashboard', [AdminController::class, 'indexAdmin'])->middleware('auth')->name('index.dashboard');

Route::get('/alumnos', function () {
    abort_if(auth()->user()->role !== 'alumno', 403);
    return view('alumnos');
})->middleware('auth')->name('alumnos.index');

Route::get('/maestros', function () {
    abort_if(auth()->user()->role !== 'maestro', 403);
    return view('maestros');
})->middleware('auth')->name('maestros.index');

Route::get('/usuarios', [AdminController::class, 'indexUsers'])->middleware('auth')->name('index.users');
Route::put('/usuarios/{user}/role', [AdminController::class, 'updateUserRole'])->middleware('auth')->name('update.users.role');

Route::get('/materias', [AdminController::class, 'indexMaterias'])->name('index.materias');
Route::post('/materias', [AdminController::class, 'createMateria'])->name('create.materias');
Route::get('/materias/{materia}/edit', [AdminController::class, 'editMateria'])->name('edit.materias');
Route::put('/materias/{materia}', [AdminController::class, 'updateMateria'])->name('update.materias');
Route::delete('/materias/{materia}', [AdminController::class, 'deleteMateria'])->name('delete.materias');

Route::get('/horarios', [AdminController::class, 'indexHorarios'])->name('index.horarios');
Route::post('/horarios', [AdminController::class, 'createHorario'])->name('create.horarios');
Route::get('/horarios/{horario}/edit', [AdminController::class, 'editHorario'])->name('edit.horarios');
Route::put('/horarios/{horario}', [AdminController::class, 'updateHorario'])->name('update.horarios');
Route::delete('/horarios/{horario}', [AdminController::class, 'deleteHorario'])->name('delete.horarios');

Route::get('/grupos', [AdminController::class, 'indexGrupos'])->name('index.grupos');
Route::post('/grupos', [AdminController::class, 'createGrupo'])->name('create.grupos');
Route::get('/grupos/{grupo}/edit', [AdminController::class, 'editGrupo'])->name('edit.grupos');
Route::put('/grupos/{grupo}', [AdminController::class, 'updateGrupo'])->name('update.grupos');
Route::delete('/grupos/{grupo}', [AdminController::class, 'deleteGrupo'])->name('delete.grupos');

Route::get('/calificaciones', [CalificacionController::class, 'index'])->name('index.calificaciones');
Route::post('/calificaciones', [CalificacionController::class, 'store'])->name('create.calificaciones');
Route::get('/calificaciones/{calificacion}/edit', [CalificacionController::class, 'edit'])->name('edit.calificaciones');
Route::put('/calificaciones/{calificacion}', [CalificacionController::class, 'update'])->name('update.calificaciones');
Route::delete('/calificaciones/{calificacion}', [CalificacionController::class, 'destroy'])->name('delete.calificaciones');

// API for calificaciones
Route::get('/api/calificaciones', [CalificacionController::class, 'apiIndex']);
Route::get('/api/calificaciones/{calificacion}', [CalificacionController::class, 'apiShow']);
Route::post('/api/calificaciones', [CalificacionController::class, 'apiStore']);
Route::put('/api/calificaciones/{calificacion}', [CalificacionController::class, 'apiUpdate']);
Route::delete('/api/calificaciones/{calificacion}', [CalificacionController::class, 'apiDestroy']);