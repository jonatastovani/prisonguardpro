<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefTiposCabeloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = '172.14.239.101';

        $insert = [
            ['nome' => 'CARAPINHO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'LISO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'CRESPO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'CALVO', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'OUTROS', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_tipos_cabelos')->insert($insert);
    }
}
