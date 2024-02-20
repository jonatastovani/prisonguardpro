<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefDocumentoTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['nome' => 'RG', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'CPF', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'CNH', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Passaporte', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Carteirinha SUS', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_documento_tipos')->insert($insert);

    }
}
