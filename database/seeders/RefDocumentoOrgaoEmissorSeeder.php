<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefDocumentoOrgaoEmissorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['id' => 1, 'sigla' => 'SSP', 'nome' => 'Secretaria de Segurança Pública', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 2, 'sigla' => 'DETRAN', 'nome' => 'Departamento de Trânsito', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 3, 'sigla' => 'CRC', 'nome' => 'Cartório de Registro Civil', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 4, 'sigla' => 'MRE', 'nome' => 'Ministério das Relações Exteriores', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 5, 'sigla' => 'INI', 'nome' => 'Instituto Nacional de Identificação', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 6, 'sigla' => 'MD', 'nome' => 'Ministério da Defesa', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 7, 'sigla' => 'MTb', 'nome' => 'Ministério do Trabalho', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 8, 'sigla' => 'MEC', 'nome' => 'Ministério da Educação', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 9, 'sigla' => 'MS', 'nome' => 'Ministério da Saúde', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 10, 'sigla' => 'TRE', 'nome' => 'Cartório Eleitoral', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 11, 'sigla' => 'MJ', 'nome' => 'Ministério da Justiça', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 12, 'sigla' => 'MF', 'nome' => 'Ministério da Fazenda', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 13, 'sigla' => 'MDS', 'nome' => 'Ministério do Desenvolvimento Social', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];
                
        DB::table('ref_documento_orgao_emissor')->insert($insert);
    }
}
