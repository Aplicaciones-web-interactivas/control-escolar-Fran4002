<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaestroSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a few sample maestro.
        User::factory(10)->create([
            'role' => 'maestro',
        ]);

        // Example specific test alumno.
        User::factory()->create([
            'name' => 'Maestro de Prueba',
            'clave_institucional' => '00003',
            'role' => 'maestro',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
    }
}
