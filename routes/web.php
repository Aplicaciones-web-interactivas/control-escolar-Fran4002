<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\TareaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

route::get('/dashboard', [AdminController::class, 'indexAdmin'])->middleware('auth')->name('index.dashboard');

Route::get('/alumnos', [TareaController::class, 'indexAlumno'])->middleware('auth')->name('alumnos.index');
Route::post('/alumnos/tareas/{tarea}/entrega', [TareaController::class, 'storeEntrega'])->middleware('auth')->name('alumnos.tareas.entrega');

Route::get('/maestros', [TareaController::class, 'indexMaestro'])->middleware('auth')->name('maestros.index');
Route::post('/maestros/tareas', [TareaController::class, 'storeTarea'])->middleware('auth')->name('create.tareas');
Route::get('/maestros/tareas/{tarea}/edit', [TareaController::class, 'editTarea'])->middleware('auth')->name('edit.tareas');
Route::put('/maestros/tareas/{tarea}', [TareaController::class, 'updateTarea'])->middleware('auth')->name('update.tareas');
Route::delete('/maestros/tareas/{tarea}', [TareaController::class, 'destroyTarea'])->middleware('auth')->name('delete.tareas');
Route::get('/maestros/tareas/{tarea}/entregas', [TareaController::class, 'showEntregasMaestro'])->middleware('auth')->name('maestros.tareas.entregas');
Route::post('/maestros/tareas/{tarea}/entregas/{entrega}/revisar', [TareaController::class, 'marcarRevisada'])->middleware('auth')->name('maestros.tareas.entregas.revisar');
Route::get('/entregas/{entrega}/archivo', [TareaController::class, 'downloadEntrega'])->middleware('auth')->name('entregas.archivo');

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
Route::post('/grupos/{grupo}/alumnos', [AdminController::class, 'addAlumnoToGrupo'])->name('store.grupos.alumnos');
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