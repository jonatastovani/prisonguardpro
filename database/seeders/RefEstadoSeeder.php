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
            ['nome' => 'Acre', 'sigla' => 'AC', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Alagoas', 'sigla' => 'AL', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Amapá', 'sigla' => 'AP', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Amazonas', 'sigla' => 'AM', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Bahia', 'sigla' => 'BA', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Ceará', 'sigla' => 'CE', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Distrito Federal', 'sigla' => 'DF', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Espírito Santo', 'sigla' => 'ES', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Goiás', 'sigla' => 'GO', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Maranhão', 'sigla' => 'MA', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Mato Grosso', 'sigla' => 'MT', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Mato Grosso do Sul', 'sigla' => 'MS', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Minas Gerais', 'sigla' => 'MG', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Pará', 'sigla' => 'PA', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Paraíba', 'sigla' => 'PB', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Paraná', 'sigla' => 'PR', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Pernambuco', 'sigla' => 'PE', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Piauí', 'sigla' => 'PI', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Rio de Janeiro', 'sigla' => 'RJ', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Rio Grande do Norte', 'sigla' => 'RN', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Rio Grande do Sul', 'sigla' => 'RS', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Rondônia', 'sigla' => 'RO', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Roraima', 'sigla' => 'RR', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Santa Catarina', 'sigla' => 'SC', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'São Paulo', 'sigla' => 'SP', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Sergipe', 'sigla' => 'SE', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Tocantins', 'sigla' => 'TO', 'id_pais' => 3, 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
        ];
        
        DB::table('ref_estados')->insert($insert);

    }
}
