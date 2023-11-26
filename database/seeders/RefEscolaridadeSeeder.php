<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefEscolaridadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = '172.14.239.101';

        $cutis = [
            ['nome' => 'ANALFABETO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'ALFABETIZADO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'FUNDAMENTAL INCOMPLETO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'FUNDAMENTAL COMPLETO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'MÉDIO INCOMPLETO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'MÉDIO COMPLETO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'SUPERIOR INCOMPLETO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'SUPERIOR COMPLETO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'MESTRADO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'DOUTORADO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_escolaridades')->insert($cutis);
    }
}
