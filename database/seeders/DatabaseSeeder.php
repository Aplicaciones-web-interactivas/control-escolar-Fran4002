<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed admin/test account.
        User::factory()->create([
            'name' => 'admin',
            'clave_institucional' => '00000',
            'password' => bcrypt('admin'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Seed alumnos.
        $this->call(AlumnoSeeder::class);
        $this->call(MaestroSeeder::class);
        $this->call(MateriaSeeder::class);
        $this->call(HorarioSeeder::class);
        $this->call(GrupoSeeder::class);
        $this->call(CalificacionSeeder::class);
    }
}
