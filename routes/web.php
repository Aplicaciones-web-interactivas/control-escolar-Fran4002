<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

route::get('/dashboard', [AdminController::class, 'indexAdmin'])->middleware('auth')->name('index.dashboard');

route::get('/materias', [AdminController::class, 'indexMaterias'])->name('index.materias');
route::post('/materias', [AdminController::class, 'createMateria'])->name('create.materias');
route::get('/materias/{materia}/edit', [AdminController::class, 'editMateria'])->name('edit.materias');
route::put('/materias/{materia}', [AdminController::class, 'updateMateria'])->name('update.materias');
route::delete('/materias/{materia}', [AdminController::class, 'deleteMateria'])->name('delete.materias');