<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefCidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['nome' => 'Americana', 'estado_id' => 25, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => "Santa Bárbara D'Oeste", 'estado_id' => 25, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Sumaré', 'estado_id' => 25, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];
        
        DB::table('ref_cidades')->insert($insert);

    }
}
