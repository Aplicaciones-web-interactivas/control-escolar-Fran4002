<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CalificacionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alumnoIds = DB::table('users')->where('role', 'alumno')->pluck('id');
        $materiaIds = DB::table('materias')->pluck('id');

        if ($alumnoIds->isEmpty() || $materiaIds->isEmpty()) {
            return;
        }

        $now = now();
        $rows = [];

        foreach ($alumnoIds as $alumnoId) {
            $cantidadMaterias = min(4, $materiaIds->count());
            $materiasAlumno = $materiaIds->random($cantidadMaterias);

            foreach ($materiasAlumno as $materiaId) {
                $rows[] = [
                    'alumno_id' => $alumnoId,
                    'materia_id' => $materiaId,
                    'calificacion' => random_int(60, 100),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('calificacions')->insert($rows);
    }
}
