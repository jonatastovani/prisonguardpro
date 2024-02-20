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
            ['id' => 1, 'nome' => 'Acre', 'sigla' => 'AC', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 2, 'nome' => 'Alagoas', 'sigla' => 'AL', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 3, 'nome' => 'Amapá', 'sigla' => 'AP', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 4, 'nome' => 'Amazonas', 'sigla' => 'AM', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 5, 'nome' => 'Bahia', 'sigla' => 'BA', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 6, 'nome' => 'Ceará', 'sigla' => 'CE', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 7, 'nome' => 'Distrito Federal', 'sigla' => 'DF', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 8, 'nome' => 'Espírito Santo', 'sigla' => 'ES', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 9, 'nome' => 'Goiás', 'sigla' => 'GO', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 10, 'nome' => 'Maranhão', 'sigla' => 'MA', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 11, 'nome' => 'Mato Grosso', 'sigla' => 'MT', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 12, 'nome' => 'Mato Grosso do Sul', 'sigla' => 'MS', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 13, 'nome' => 'Minas Gerais', 'sigla' => 'MG', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 14, 'nome' => 'Pará', 'sigla' => 'PA', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 15, 'nome' => 'Paraíba', 'sigla' => 'PB', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 16, 'nome' => 'Paraná', 'sigla' => 'PR', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 17, 'nome' => 'Pernambuco', 'sigla' => 'PE', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 18, 'nome' => 'Piauí', 'sigla' => 'PI', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 19, 'nome' => 'Rio de Janeiro', 'sigla' => 'RJ', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 20, 'nome' => 'Rio Grande do Norte', 'sigla' => 'RN', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 21, 'nome' => 'Rio Grande do Sul', 'sigla' => 'RS', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 22, 'nome' => 'Rondônia', 'sigla' => 'RO', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 23, 'nome' => 'Roraima', 'sigla' => 'RR', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 24, 'nome' => 'Santa Catarina', 'sigla' => 'SC', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 25, 'nome' => 'São Paulo', 'sigla' => 'SP', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 26, 'nome' => 'Sergipe', 'sigla' => 'SE', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 27, 'nome' => 'Tocantins', 'sigla' => 'TO', 'nacionalidade_id' => 3, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];
        
        DB::table('ref_estados')->insert($insert);

    }
}
