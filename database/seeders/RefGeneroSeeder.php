<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefGeneroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['nome' => 'Heterossexual/Masculino', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Heterossexual/Feminino', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_generos')->insert($insert);

    }
}
