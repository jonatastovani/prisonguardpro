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
            ['tipo_id' => 1, 'motivo_id' => 17, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 2, 'motivo_id' => 64, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 1, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 4, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 7, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 8, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 13, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 14, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 16, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 19, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 20, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 21, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 22, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 23, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 24, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 27, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 28, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 29, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 30, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 34, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 35, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 40, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 44, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 45, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 46, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 49, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 52, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 53, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 54, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 55, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 56, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 57, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 64, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 77, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 78, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 3, 'motivo_id' => 79, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 2, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 6, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 9, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 10, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 11, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 15, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 25, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 31, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 38, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 41, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 43, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 4, 'motivo_id' => 60, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 6, 'motivo_id' => 5, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 8, 'motivo_id' => 22, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 8, 'motivo_id' => 29, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 8, 'motivo_id' => 30, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 11, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 15, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 36, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 37, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 42, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 43, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 48, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 50, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 51, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 58, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 9, 'motivo_id' => 59, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 2, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 6, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 9, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 10, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 11, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 15, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 25, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 31, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 38, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 39, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 41, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 43, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 10, 'motivo_id' => 60, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 12, 'motivo_id' => 5, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 12, 'motivo_id' => 32, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 15, 'motivo_id' => 18, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 15, 'motivo_id' => 26, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 15, 'motivo_id' => 32, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 15, 'motivo_id' => 33, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 15, 'motivo_id' => 47, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 15, 'motivo_id' => 61, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 15, 'motivo_id' => 62, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 16, 'motivo_id' => 5, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 5, 'motivo_id' => 2, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['tipo_id' => 5, 'motivo_id' => 5, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];
        
        DB::table('ref_movimentacao_preso')->insert($insert);
    }
}
