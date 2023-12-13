<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncEntradaOrigemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = $_SERVER['REMOTE_ADDR'];

        $insert = [
            ['id' => 1, 'nome' => '01 D.P. A AMERICANA', 'id_user_created' => 54, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 2, 'nome' => '02 D.P. A AMERICANA', 'id_user_created' => 54, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 3, 'nome' => 'DEL. SEC. AMERICANA', 'id_user_created' => 54, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 4, 'nome' => '01 D.P. A SUMARE', 'id_user_created' => 54, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 5, 'nome' => '02 D.P. A SUMARE', 'id_user_created' => 54, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 6, 'nome' => 'CAD.PUB.SUMARE', 'id_user_created' => 54, 'ip_created' => $iplocal, 'created_at' => now()],
        ];

        DB::table('inc_entrada_origem')->insert($insert);
    }
}
