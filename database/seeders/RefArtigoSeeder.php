<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefArtigoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = '172.14.239.101';

        $insert = [
            ['nome' => '33', 'descricao' => 'Tráfico', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => '155', 'descricao' => 'Roubo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => '121', 'descricao' => 'Ameaça', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_artigos')->insert($insert);

    }
}
