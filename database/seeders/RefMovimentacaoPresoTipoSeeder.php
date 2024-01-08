<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefMovimentacaoPresoTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['id' => 1, 'sigla' => 'AB', 'nome' => 'Abandono', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 2, 'sigla' => 'EV', 'nome' => 'Evasão', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 3, 'sigla' => 'EX', 'nome' => 'Exclusão', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 4, 'sigla' => 'ER', 'nome' => 'Exclusão por Remoção', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 5, 'sigla' => 'EE', 'nome' => 'Exclusão por Retorno', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 6, 'sigla' => 'ET', 'nome' => 'Exclusão por Trânsito', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 7, 'sigla' => 'EP', 'nome' => 'Exclusão do Trânsito', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 8, 'sigla' => 'FA', 'nome' => 'Falecimento', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 9, 'sigla' => 'IN', 'nome' => 'Inclusão', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 10, 'sigla' => 'IR', 'nome' => 'Inclusão por Remoção', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 11, 'sigla' => 'IE', 'nome' => 'Inclusão por Retorno', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 12, 'sigla' => 'IT', 'nome' => 'Inclusão por Trânsito', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 13, 'sigla' => 'MS', 'nome' => 'Mudança de Tipo de Vaga - Saída', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 14, 'sigla' => 'ME', 'nome' => 'Mudança de Tipo de Vaga - Entrada', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 15, 'sigla' => 'TE', 'nome' => 'Trânsito Externo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 16, 'sigla' => 'RT', 'nome' => 'Retorno', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
        ];
        
        DB::table('ref_movimentacao_preso_tipos')->insert($insert);
    }
}
