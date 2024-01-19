<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefCabeloCorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['nome' => 'Castanho', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Preto', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Louro', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Ruivo', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Grisalho', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Branco', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Careca', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Outros', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_cabelo_cor')->insert($insert);

    }
}
