<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefMovimentacaoPresoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['tipo_id' => 1, 'motivo_id' => 17, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 2, 'motivo_id' => 64, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 1, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 4, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 7, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 8, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 13, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 14, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 16, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 19, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 20, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 21, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 22, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 23, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 24, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 27, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 28, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 29, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 30, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 34, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 35, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 40, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 44, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 45, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 46, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 49, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 52, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 53, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 54, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 55, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 56, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 57, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 64, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 77, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 78, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 79, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 2, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 6, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 9, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 10, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 11, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 15, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 25, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 31, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 38, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 41, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 43, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 60, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 6, 'motivo_id' => 5, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 8, 'motivo_id' => 22, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 8, 'motivo_id' => 29, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 8, 'motivo_id' => 30, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 11, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 15, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 36, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 37, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 42, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 43, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 48, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 50, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 51, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 58, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 59, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 2, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 6, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 9, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 10, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 11, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 15, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 25, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 31, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 38, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 39, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 41, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 43, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 60, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 12, 'motivo_id' => 5, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 12, 'motivo_id' => 32, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 15, 'motivo_id' => 18, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 15, 'motivo_id' => 26, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 15, 'motivo_id' => 32, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 15, 'motivo_id' => 33, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 15, 'motivo_id' => 47, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 15, 'motivo_id' => 61, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 15, 'motivo_id' => 62, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 16, 'motivo_id' => 5, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 5, 'motivo_id' => 2, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 5, 'motivo_id' => 5, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
        ];
        
        DB::table('ref_movimentacao_preso')->insert($insert);
    }
}
