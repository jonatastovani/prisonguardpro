<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefTurnoTipoPermissaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insert = [
            ['nome' => 'Diretor de Serviço do Núcleo de Segurança'],
            ['nome' => 'Diretor de Serviço de Portaria'],
            ['nome' => 'Chefia Penal'],
        ];

        DB::table('ref_turno_tipo_permissao')->insert($insert);
    }
}
