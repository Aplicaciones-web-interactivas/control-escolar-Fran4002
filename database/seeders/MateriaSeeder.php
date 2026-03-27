<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MateriaSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('materias')->insert([
            ['nombre' => 'Matematicas I', 'clave' => 'MAT-101', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Fisica Basica', 'clave' => 'FIS-101', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Quimica General', 'clave' => 'QUI-101', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Historia de Mexico', 'clave' => 'HIS-102', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Literatura', 'clave' => 'LIT-103', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Ingles I', 'clave' => 'ING-101', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Informatica', 'clave' => 'INF-110', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Biologia', 'clave' => 'BIO-104', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
