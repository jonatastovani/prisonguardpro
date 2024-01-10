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
            ['id' => 1, 'nome' => 'Administrador do Sistema', 'individuais' => true],
            ['id' => 2, 'nome' => 'Diretoria Geral/Supervisão', 'individuais' => true],
            ['id' => 3, 'nome' => 'Diretoria de Centro', 'individuais' => true],
            ['id' => 4, 'nome' => 'Gerenciar Administradores de Núcleos', 'individuais' => true],
            ['id' => 5, 'nome' => 'Entradas de presos', 'individuais' => false],
            ['id' => 6, 'nome' => 'Gerenciar Entradas', 'individuais' => false],
            ['id' => 7, 'nome' => 'Gerenciar Ítens do Kit', 'individuais' => false],
            ['id' => 8, 'nome' => 'Gerenciar Ítens Pertences', 'individuais' => false],
            ['id' => 9, 'nome' => 'Gerenciar Pertences Guardados', 'individuais' => false],
            ['id' => 10, 'nome' => 'Gerenciar Sedex Retidos', 'individuais' => false],
            ['id' => 11, 'nome' => 'Inclusão e Dados Cadastrais', 'individuais' => false],
            ['id' => 12, 'nome' => 'Gerenciar Entrada Inclusão', 'individuais' => false],
            ['id' => 13, 'nome' => 'Gerenciar Listagem Artigos', 'individuais' => false],
            ['id' => 14, 'nome' => 'Gerenciar Apresentações', 'individuais' => false],
            ['id' => 15, 'nome' => 'Gerenciar Transferências', 'individuais' => false],
            ['id' => 16, 'nome' => 'Gerenciar Unidades Prisionais', 'individuais' => false],
            ['id' => 17, 'nome' => 'Gerenciar Penal', 'individuais' => true],
            ['id' => 18, 'nome' => 'Zeladoria de Raios/Celas', 'individuais' => true],
            ['id' => 19, 'nome' => 'Gerenciar Listagem Origens Inclusões', 'individuais' => false],
            ['id' => 20, 'nome' => 'Gerenciar Listagem Tipos de Documentos', 'individuais' => false],
            ['id' => 21, 'nome' => 'Gerenciar Listagem Profissões', 'individuais' => false],
        ];        

        DB::table('ref_permissao_grupo')->insert($insert);

    }
}
