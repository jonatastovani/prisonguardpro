<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefPermissaoGrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insert = [
            ['nome' => 'Administrador do Sistema', 'individuais' => true],
            ['nome' => 'Diretoria Geral/Supervisão', 'individuais' => true],
            ['nome' => 'Diretoria de Centro', 'individuais' => true],
            ['nome' => 'Gerenciar Administradores de Núcleos', 'individuais' => true],
            ['nome' => 'Entradas de presos', 'individuais' => false],
            ['nome' => 'Gerenciar Entradas', 'individuais' => false],
            ['nome' => 'Gerenciar Ítens do Kit', 'individuais' => false],
            ['nome' => 'Gerenciar Ítens Pertences', 'individuais' => false],
            ['nome' => 'Gerenciar Pertences Guardados', 'individuais' => false],
            ['nome' => 'Gerenciar Sedex Retidos', 'individuais' => false],
            ['nome' => 'Inclusão e Dados Cadastrais', 'individuais' => false],
            ['nome' => 'Gerenciar Entrada Inclusão', 'individuais' => false],
            ['nome' => 'Gerenciar Listagem Artigos', 'individuais' => false],
            ['nome' => 'Gerenciar Apresentações', 'individuais' => false],
            ['nome' => 'Gerenciar Transferências', 'individuais' => false],
            ['nome' => 'Gerenciar Unidades Prisionais', 'individuais' => false],
            ['nome' => 'Gerenciar Penal', 'individuais' => true],
            ['nome' => 'Zeladoria de Raios/Celas', 'individuais' => true],
        ];

        DB::table('ref_permissao_grupo')->insert($insert);

    }
}
