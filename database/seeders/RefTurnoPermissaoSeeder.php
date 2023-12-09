<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefTurnoPermissaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insert = [
            ['turno_id' => 1, 'tipo_permissao_id' => 1, 'permissao_id' => 54],
            ['turno_id' => 2, 'tipo_permissao_id' => 1, 'permissao_id' => 55],
            ['turno_id' => 3, 'tipo_permissao_id' => 1, 'permissao_id' => 56],
            ['turno_id' => 4, 'tipo_permissao_id' => 1, 'permissao_id' => 57],
            ['turno_id' => 5, 'tipo_permissao_id' => 1, 'permissao_id' => 53],
            ['turno_id' => 1, 'tipo_permissao_id' => 3, 'permissao_id' => 58],
            ['turno_id' => 2, 'tipo_permissao_id' => 3, 'permissao_id' => 59],
            ['turno_id' => 3, 'tipo_permissao_id' => 3, 'permissao_id' => 60],
            ['turno_id' => 4, 'tipo_permissao_id' => 3, 'permissao_id' => 61],
        ];

        DB::table('ref_turno_permissao')->insert($insert);
    }
}
