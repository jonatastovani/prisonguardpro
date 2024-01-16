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
            ['id' => 1, 'sigla' => 'SSP', 'nome' => 'Secretaria de Segurança Pública', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 2, 'sigla' => 'DETRAN', 'nome' => 'Departamento de Trânsito', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 3, 'sigla' => 'CRC', 'nome' => 'Cartório de Registro Civil', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 4, 'sigla' => 'MRE', 'nome' => 'Ministério das Relações Exteriores', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 5, 'sigla' => 'INI', 'nome' => 'Instituto Nacional de Identificação', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 6, 'sigla' => 'MD', 'nome' => 'Ministério da Defesa', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 7, 'sigla' => 'MTb', 'nome' => 'Ministério do Trabalho', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 8, 'sigla' => 'MEC', 'nome' => 'Ministério da Educação', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 9, 'sigla' => 'MS', 'nome' => 'Ministério da Saúde', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 10, 'sigla' => 'TRE', 'nome' => 'Cartório Eleitoral', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 11, 'sigla' => 'MJ', 'nome' => 'Ministério da Justiça', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 12, 'sigla' => 'MF', 'nome' => 'Ministério da Fazenda', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 13, 'sigla' => 'MDS', 'nome' => 'Ministério do Desenvolvimento Social', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
        ];
                
        DB::table('ref_documento_orgao_emissor')->insert($insert);
    }
}
