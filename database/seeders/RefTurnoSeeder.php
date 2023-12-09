<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Testing\Fakes\Fake;

class RefTurnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insert = [
            ['nome' => 'Turno I', 'periodo_diurno_bln' => true, 'plantao_bln' => true],
            ['nome' => 'Turno II', 'periodo_diurno_bln' => false, 'plantao_bln' => true],
            ['nome' => 'Turno III', 'periodo_diurno_bln' => true, 'plantao_bln' => true],
            ['nome' => 'Turno IV', 'periodo_diurno_bln' => false, 'plantao_bln' => true],
            ['nome' => 'Diarista', 'periodo_diurno_bln' => true, 'plantao_bln' => false],
        ];

        DB::table('ref_turnos')->insert($insert);
    }
}
