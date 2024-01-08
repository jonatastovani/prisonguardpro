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
            ['tipo_id' => 4, 'motivo_final' => 'Remoção', 'motivo_intermediario' => 'Aguardar Remoção', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 5, 'motivo_final' => 'Ret. Trânsito', 'motivo_intermediario' => 'Ret. Trânsito', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 6, 'motivo_final' => 'Trânsito', 'motivo_intermediario' => 'Trânsito', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_final' => 'Remoção', 'motivo_intermediario' => NULL, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
        ];
                
        DB::table('ref_movimentacao_preso_tipo_configs')->insert($insert);

    }
}
