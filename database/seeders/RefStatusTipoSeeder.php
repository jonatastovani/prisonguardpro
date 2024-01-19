<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefStatusTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['id' => 1, 'nome' => 'Gerenciar Entradas de Presos CIMIC', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 2, 'nome' => 'Gerenciar Entradas de Presos Inclusão', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_status_tipos')->insert($insert);
    }
}
