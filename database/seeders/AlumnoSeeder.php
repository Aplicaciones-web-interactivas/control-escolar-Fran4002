<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlumnoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a few sample alumnos.
        User::factory(10)->create([
            'role' => 'alumno',
        ]);

        // Example specific test alumno.
        User::factory()->create([
            'name' => 'Alumno de Prueba',
            'clave_institucional' => '30001',
            'role' => 'alumno',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
    }
}
