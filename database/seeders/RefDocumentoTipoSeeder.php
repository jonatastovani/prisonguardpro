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
            ['nome' => 'CPF', 'doc_nacional_bln' => true, 'bloqueado_perm_adm_bln' => true, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'RG', 'doc_nacional_bln' => false, 'bloqueado_perm_adm_bln' => true, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'CNH', 'doc_nacional_bln' => true, 'bloqueado_perm_adm_bln' => true, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Passaporte', 'doc_nacional_bln' => true, 'bloqueado_perm_adm_bln' => true, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Carteirinha SUS', 'doc_nacional_bln' => true, 'bloqueado_perm_adm_bln' => true, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_documento_tipos')->insert($insert);
    }
}
