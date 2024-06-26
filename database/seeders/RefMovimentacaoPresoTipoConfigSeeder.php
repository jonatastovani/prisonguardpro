<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefMovimentacaoPresoTipoConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['tipo_id' => 4, 'motivo_final' => 'Remoção', 'motivo_intermediario' => 'Aguardar Remoção', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 5, 'motivo_final' => 'Ret. Trânsito', 'motivo_intermediario' => 'Ret. Trânsito', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 6, 'motivo_final' => 'Trânsito', 'motivo_intermediario' => 'Trânsito', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_final' => 'Remoção', 'motivo_intermediario' => NULL, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];
                
        DB::table('ref_movimentacao_preso_tipo_configs')->insert($insert);

    }
}
