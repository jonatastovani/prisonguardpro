<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
USE Illuminate\Support\Facades\DB;

class RefEstadoCivilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = '172.14.239.101';

        $insert = [
            ['nome' => 'SOLTEIRO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'UNIÃO ESTÁVEL', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'CASADO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'DESQUITADO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'DIVORCIADO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'VIÚVO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'OUTROS', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'NÃO INFORMADO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_estado_civil')->insert($insert);
    }
}
