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
        $iplocal = '172.14.239.101';

        $estados = [
            ['nome' => 'Acre', 'sigla' => 'AC', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Alagoas', 'sigla' => 'AL', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Amapá', 'sigla' => 'AP', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Amazonas', 'sigla' => 'AM', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Bahia', 'sigla' => 'BA', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Ceará', 'sigla' => 'CE', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Distrito Federal', 'sigla' => 'DF', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Espírito Santo', 'sigla' => 'ES', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Goiás', 'sigla' => 'GO', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Maranhão', 'sigla' => 'MA', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Mato Grosso', 'sigla' => 'MT', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Mato Grosso do Sul', 'sigla' => 'MS', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Minas Gerais', 'sigla' => 'MG', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Pará', 'sigla' => 'PA', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Paraíba', 'sigla' => 'PB', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Paraná', 'sigla' => 'PR', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Pernambuco', 'sigla' => 'PE', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Piauí', 'sigla' => 'PI', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Rio de Janeiro', 'sigla' => 'RJ', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Rio Grande do Norte', 'sigla' => 'RN', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Rio Grande do Sul', 'sigla' => 'RS', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Rondônia', 'sigla' => 'RO', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Roraima', 'sigla' => 'RR', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Santa Catarina', 'sigla' => 'SC', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'São Paulo', 'sigla' => 'SP', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Sergipe', 'sigla' => 'SE', 'id_user_created' => 1, 'ip_created' => $iplocal],
            ['nome' => 'Tocantins', 'sigla' => 'TO', 'id_user_created' => 1, 'ip_created' => $iplocal],
        ];
        
        DB::table('ref_estados')->insert($estados);

    }
}
