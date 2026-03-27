<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HorarioSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $maestroIds = DB::table('users')->where('role', 'maestro')->pluck('id');
        $materiaIds = DB::table('materias')->pluck('id');

        if ($maestroIds->isEmpty() || $materiaIds->isEmpty()) {
            return;
        }

        $bloques = [
            ['dias' => 'Lunes, Miercoles', 'hora_inicio' => '07:00:00', 'hora_fin' => '08:30:00'],
            ['dias' => 'Martes, Jueves', 'hora_inicio' => '08:30:00', 'hora_fin' => '10:00:00'],
            ['dias' => 'Lunes, Viernes', 'hora_inicio' => '10:00:00', 'hora_fin' => '11:30:00'],
            ['dias' => 'Miercoles, Viernes', 'hora_inicio' => '11:30:00', 'hora_fin' => '13:00:00'],
            ['dias' => 'Martes, Jueves', 'hora_inicio' => '13:00:00', 'hora_fin' => '14:30:00'],
            ['dias' => 'Lunes, Miercoles', 'hora_inicio' => '14:30:00', 'hora_fin' => '16:00:00'],
        ];

        $now = now();
        $rows = [];
        $totalMaestros = $maestroIds->count();
        $totalMaterias = $materiaIds->count();

        foreach ($bloques as $index => $bloque) {
            $rows[] = [
                'maestro_id' => $maestroIds[$index % $totalMaestros],
                'materia_id' => $materiaIds[$index % $totalMaterias],
                'dias' => $bloque['dias'],
                'hora_inicio' => $bloque['hora_inicio'],
                'hora_fin' => $bloque['hora_fin'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('horarios')->insert($rows);
    }
}
