<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrupoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alumnoIds = DB::table('users')->where('role', 'alumno')->pluck('id');
        $horarioIds = DB::table('horarios')->pluck('id');

        if ($alumnoIds->isEmpty() || $horarioIds->isEmpty()) {
            return;
        }

        $now = now();
        $rows = [];

        foreach ($alumnoIds as $alumnoId) {
            $cantidadAsignaciones = min(2, $horarioIds->count());
            $asignaciones = $horarioIds->random($cantidadAsignaciones);

            foreach ($asignaciones as $horarioId) {
                $rows[] = [
                    'alumno_id' => $alumnoId,
                    'horario_id' => $horarioId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('grupos')->insert($rows);
    }
}
