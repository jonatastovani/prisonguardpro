<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefTipoMovimentacaoPresoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = $_SERVER['REMOTE_ADDR'];

        $insert = [
            ['id' => 1, 'nome' => 'Inclusão', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 2, 'nome' => 'Transferência/Remoção', 'id_user_created' => 54, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 3, 'nome' => 'Trânsito', 'id_user_created' => 54, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 4, 'nome' => 'Retorno Trânsito', 'id_user_created' => 54, 'ip_created' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_tipo_movimentacao_preso')->insert($insert);
    }
}
