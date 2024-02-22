<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefDocumentoConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['tipo_id' => 1, 'mask' => '3', 'comprimento_int' => '3', 'validade_emissao_int' => 'RG', 'estado_id' => 'RG', 'orgao_exp_id' => 'RG', 'nacionalidade_id' => 'RG', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            
        ];

        DB::table('ref_documento_configs')->insert($insert);
    }
}
