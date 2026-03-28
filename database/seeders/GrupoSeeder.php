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
        $maestroPruebaId = DB::table('users')
            ->where('role', 'maestro')
            ->where('name', 'Maestro de Prueba')
            ->value('id');
        $usuarioPruebaId = DB::table('users')
            ->where('role', 'alumno')
            ->whereIn('name', ['Usuario de Prueba', 'Usuario de prueba', 'Alumno de Prueba'])
            ->value('id');
        $horariosMaestroPrueba = $maestroPruebaId
            ? DB::table('horarios')->where('maestro_id', $maestroPruebaId)->pluck('id')
            : collect();

        if ($alumnoIds->isEmpty() || $horarioIds->isEmpty()) {
            return;
        }

        $now = now();
        $rows = [];

        foreach ($alumnoIds as $alumnoId) {
            $cantidadAsignaciones = min(2, $horarioIds->count());
            $asignaciones = $cantidadAsignaciones === 1
                ? collect([$horarioIds->random()])
                : $horarioIds->random($cantidadAsignaciones);

            if (
                $usuarioPruebaId &&
                $alumnoId === $usuarioPruebaId &&
                $horariosMaestroPrueba->isNotEmpty()
            ) {
                $horarioMaestroPruebaId = $horariosMaestroPrueba->first();
                $asignaciones = collect([$horarioMaestroPruebaId]);

                if ($cantidadAsignaciones > 1) {
                    $horariosRestantes = $horarioIds
                        ->reject(fn ($horarioId) => $horarioId === $horarioMaestroPruebaId)
                        ->values();

                    if ($horariosRestantes->isNotEmpty()) {
                        $faltantes = min($cantidadAsignaciones - 1, $horariosRestantes->count());
                        $asignacionesExtra = $faltantes === 1
                            ? collect([$horariosRestantes->random()])
                            : $horariosRestantes->random($faltantes);
                        $asignaciones = $asignaciones->merge($asignacionesExtra);
                    }
                }
            }

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
