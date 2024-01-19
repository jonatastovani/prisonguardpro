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
            ['id' => 1, 'tipo_id' => 1, 'nome_id' => 1, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 2, 'tipo_id' => 1, 'nome_id' => 2, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 3, 'tipo_id' => 1, 'nome_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 4, 'tipo_id' => 2, 'nome_id' => 4, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 5, 'tipo_id' => 2, 'nome_id' => 5, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 6, 'tipo_id' => 2, 'nome_id' => 6, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_status')->insert($insert);
    }
}
