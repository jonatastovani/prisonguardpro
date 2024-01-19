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
            ['id' => 1, 'nome' => 'Acre', 'sigla' => 'AC', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 2, 'nome' => 'Alagoas', 'sigla' => 'AL', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 3, 'nome' => 'Amapá', 'sigla' => 'AP', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 4, 'nome' => 'Amazonas', 'sigla' => 'AM', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 5, 'nome' => 'Bahia', 'sigla' => 'BA', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 6, 'nome' => 'Ceará', 'sigla' => 'CE', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 7, 'nome' => 'Distrito Federal', 'sigla' => 'DF', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 8, 'nome' => 'Espírito Santo', 'sigla' => 'ES', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 9, 'nome' => 'Goiás', 'sigla' => 'GO', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 10, 'nome' => 'Maranhão', 'sigla' => 'MA', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 11, 'nome' => 'Mato Grosso', 'sigla' => 'MT', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 12, 'nome' => 'Mato Grosso do Sul', 'sigla' => 'MS', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 13, 'nome' => 'Minas Gerais', 'sigla' => 'MG', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 14, 'nome' => 'Pará', 'sigla' => 'PA', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 15, 'nome' => 'Paraíba', 'sigla' => 'PB', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 16, 'nome' => 'Paraná', 'sigla' => 'PR', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 17, 'nome' => 'Pernambuco', 'sigla' => 'PE', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 18, 'nome' => 'Piauí', 'sigla' => 'PI', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 19, 'nome' => 'Rio de Janeiro', 'sigla' => 'RJ', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 20, 'nome' => 'Rio Grande do Norte', 'sigla' => 'RN', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 21, 'nome' => 'Rio Grande do Sul', 'sigla' => 'RS', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 22, 'nome' => 'Rondônia', 'sigla' => 'RO', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 23, 'nome' => 'Roraima', 'sigla' => 'RR', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 24, 'nome' => 'Santa Catarina', 'sigla' => 'SC', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 25, 'nome' => 'São Paulo', 'sigla' => 'SP', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 26, 'nome' => 'Sergipe', 'sigla' => 'SE', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 27, 'nome' => 'Tocantins', 'sigla' => 'TO', 'pais_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];
        
        DB::table('ref_estados')->insert($insert);

    }
}
