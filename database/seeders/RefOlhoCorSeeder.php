<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefOlhoCorSeeder extends Seeder
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
            ['nome' => 'Verde', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Azul', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Outros', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_olho_cor')->insert($insert);
    }
}
