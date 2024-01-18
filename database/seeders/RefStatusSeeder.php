<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['id' => 1, 'tipo_id' => 1, 'nome_id' => 1, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 2, 'tipo_id' => 1, 'nome_id' => 2, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 3, 'tipo_id' => 1, 'nome_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 4, 'tipo_id' => 2, 'nome_id' => 4, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 5, 'tipo_id' => 2, 'nome_id' => 5, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 6, 'tipo_id' => 2, 'nome_id' => 6, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_status')->insert($insert);
    }
}
