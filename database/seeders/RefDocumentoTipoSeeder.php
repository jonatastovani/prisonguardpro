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
            ['id'=> 1, 'nome' => 'CPF', 'doc_nacional_bln' => true, 'bloqueado_perm_adm_bln' => true, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 2, 'nome' => 'RG', 'doc_nacional_bln' => false, 'bloqueado_perm_adm_bln' => true, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 3, 'nome' => 'CNH', 'doc_nacional_bln' => true, 'bloqueado_perm_adm_bln' => true, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 4, 'nome' => 'Passaporte', 'doc_nacional_bln' => true, 'bloqueado_perm_adm_bln' => true, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 5, 'nome' => 'Carteirinha SUS', 'doc_nacional_bln' => true, 'bloqueado_perm_adm_bln' => true, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_documento_tipos')->insert($insert);
    }
}
