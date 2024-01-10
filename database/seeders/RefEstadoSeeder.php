<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefEstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['nome' => 'Acre', 'sigla' => 'AC', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Alagoas', 'sigla' => 'AL', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Amapá', 'sigla' => 'AP', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Amazonas', 'sigla' => 'AM', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Bahia', 'sigla' => 'BA', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Ceará', 'sigla' => 'CE', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Distrito Federal', 'sigla' => 'DF', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Espírito Santo', 'sigla' => 'ES', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Goiás', 'sigla' => 'GO', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Maranhão', 'sigla' => 'MA', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Mato Grosso', 'sigla' => 'MT', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Mato Grosso do Sul', 'sigla' => 'MS', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Minas Gerais', 'sigla' => 'MG', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Pará', 'sigla' => 'PA', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Paraíba', 'sigla' => 'PB', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Paraná', 'sigla' => 'PR', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Pernambuco', 'sigla' => 'PE', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Piauí', 'sigla' => 'PI', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Rio de Janeiro', 'sigla' => 'RJ', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Rio Grande do Norte', 'sigla' => 'RN', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Rio Grande do Sul', 'sigla' => 'RS', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Rondônia', 'sigla' => 'RO', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Roraima', 'sigla' => 'RR', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Santa Catarina', 'sigla' => 'SC', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'São Paulo', 'sigla' => 'SP', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Sergipe', 'sigla' => 'SE', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Tocantins', 'sigla' => 'TO', 'pais_id' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
        ];
        
        DB::table('ref_estados')->insert($insert);

    }
}
