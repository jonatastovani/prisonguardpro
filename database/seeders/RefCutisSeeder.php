<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefCutisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = '172.14.239.101';

        $insert = [
            ['nome' => 'BRANCA', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'PARDA', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'NEGRA', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'AMARELA', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'VERMELHA', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'OUTRAS', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_cutis')->insert($insert);
    }
}
