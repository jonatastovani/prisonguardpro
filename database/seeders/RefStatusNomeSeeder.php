<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefStatusNomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['id' => 1, 'nome' => 'Aguardando cadastro do CIMIC', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 2, 'nome' => 'Cadastro cancelado', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 3, 'nome' => 'Liberado para inclusão', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 4, 'nome' => 'Inclusão pendente', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 5, 'nome' => 'Inclusão OK', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 6, 'nome' => 'Aguardando designação de cela', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_status_nomes')->insert($insert);
    }
}
