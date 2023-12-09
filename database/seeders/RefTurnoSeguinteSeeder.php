<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefTurnoSeguinteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insert = [
            ['turno_id' => 1, 'turno_seguinte_id' => 2],
            ['turno_id' => 2, 'turno_seguinte_id' => 3],
            ['turno_id' => 3, 'turno_seguinte_id' => 4],
            ['turno_id' => 4, 'turno_seguinte_id' => 1],
        ];

        DB::table('ref_turno_seguinte')->insert($insert);
    }
}
