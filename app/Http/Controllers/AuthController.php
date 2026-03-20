<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'clave_institucional' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['clave_institucional' => $request->clave_institucional, 'password' => $request->password, 'is_active' => true])) {
            $request->session()->regenerate();
            $user = Auth::user();

            $routeName = match ($user->role) {
                'admin' => 'index.dashboard',
                'alumno' => 'alumnos.index',
                'maestro' => 'maestros.index',
                default => 'index.dashboard',
            };

            return redirect()->route($routeName);
        }

        return back()->withErrors([
            'clave_institucional' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'clave_institucional' => 'required|string|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'clave_institucional' => $request->clave_institucional,
            'password' => Hash::make($request->password),
            // Registration accounts are created as students by default.
            'role' => 'alumno',
        ]);

        return redirect('/login')->with('success', 'Account created successfully. Please log in.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}