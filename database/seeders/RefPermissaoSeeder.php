<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefPermissaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'nome' => 'Desenvolvedor',
                'nome_completo' => null,
                'descricao' => 'Permissão máxima para todas áreas do Sistema.',
            ],
            [
                'id' => 2,
                'nome' => 'Administrador Sistema',
                'nome_completo' => null,
                'descricao' => 'Permissão total a todas áreas do Sistema, tendo como exceção às áreas do Desenvolvedor.',
            ],
            [
                'id' => 3,
                'nome' => 'D.N.I',
                'nome_completo' => null,
                'descricao' => 'Diretor do Núcleo de Inclusão. Permissão total à todas áreas da Inclusão, podendo atribuir permissões para usuários neste setor.',
            ],
            [
                'id' => 4,
                'nome' => 'Incluir Presos Inclusão',
                'nome_completo' => null,
                'descricao' => 'Incluir presos no setor de Inclusão.',
            ],
            [
                'id' => 5,
                'nome' => 'Alterar Presos Inclusão',
                'nome_completo' => null,
                'descricao' => 'Alterar presos no setor de Inclusão.',
            ],
            [
                'id' => 6,
                'nome' => 'Excluir Presos Inclusão',
                'nome_completo' => null,
                'descricao' => 'Excluir presos no setor de Inclusão.',
            ],
            [
                'id' => 7,
                'nome' => 'Imprimir Entrada de Presos',
                'nome_completo' => null,
                'descricao' => 'Imprimir as entradas de preso do setor de Inclusão',
            ],
            [
                'id' => 8,
                'nome' => 'Imprimir Ficha de Digitais de Presos',
                'nome_completo' => null,
                'descricao' => 'Imprimir as fichas para colher papiloscopia dos presos',
            ],
            [
                'id' => 9,
                'nome' => 'Diretor CIMIC',
                'nome_completo' => 'Diretor Técnico II - CIMIC',
                'descricao' => 'Permissão total à todas áreas do CIMIC, podendo atribuir permissões para usuários neste setor.',
            ],
            [
                'id' => 10,
                'nome' => 'Alterar Qualificativa de Preso',
                'nome_completo' => null,
                'descricao' => 'Alterar as informações da Qualificativa do Preso.',
            ],
            [
                'id' => 11,
                'nome' => 'Incluir Presos na Unidade',
                'nome_completo' => null,
                'descricao' => 'Incluir presos na Unidade após ser lançado incluso pela Inclusão.',
            ],
            [
                'id' => 12,
                'nome' => 'Alterar Qualificativa de Preso',
                'nome_completo' => null,
                'descricao' => 'Alterar as informações da Qualificativa do Preso.',
            ],
            [
                'id' => 13,
                'nome' => 'Incluir Movimentações de Preso',
                'nome_completo' => null,
                'descricao' => 'Incluir Movimentações de Presos.',
            ],
            [
                'id' => 14,
                'nome' => 'Alterar Movimentações de Preso',
                'nome_completo' => null,
                'descricao' => 'Alterar Movimentações de Presos.',
            ],
            [
                'id' => 15,
                'nome' => 'Excluir Movimentações de Preso',
                'nome_completo' => null,
                'descricao' => 'Excluir Movimentações de Presos.',
            ],
            [
                'id' => 16,
                'nome' => 'Incluir Presos Inclusão',
                'nome_completo' => null,
                'descricao' => 'Incluir presos no setor de Inclusão.',
            ],
            [
                'id' => 17,
                'nome' => 'Alterar Presos Inclusão',
                'nome_completo' => null,
                'descricao' => 'Alterar presos no setor de Inclusão.',
            ],
            [
                'id' => 18,
                'nome' => 'Excluir Presos Inclusão',
                'nome_completo' => null,
                'descricao' => 'Excluir presos no setor de Inclusão.',
            ],
            [
                'id' => 19,
                'nome' => 'Alterar listagem de Artigos',
                'nome_completo' => null,
                'descricao' => 'Alterar ítens da listagem personalizada de Artigos.',
            ],
            [
                'id' => 20,
                'nome' => 'Excluir listagem de Artigos',
                'nome_completo' => null,
                'descricao' => 'Excluir ítens da listagem personalizada de Artigos.',
            ],
            [
                'id' => 21,
                'nome' => 'Imprimir Termo de Abertura',
                'nome_completo' => null,
                'descricao' => 'Imprimir Termo de Abertura das Inclusões do Preso.',
            ],
            [
                'id' => 22,
                'nome' => 'Excluir Entrada de Presos',
                'nome_completo' => null,
                'descricao' => 'Excluir a Entrada de Presos e todos os presos inclusos.',
            ],
            [
                'id' => 23,
                'nome' => 'Imprimir Termo de Declaração',
                'nome_completo' => null,
                'descricao' => 'Imprimir Termo de Declaração Integridade Física.',
            ],
            [
                'id' => 24,
                'nome' => 'Incluir Kit Preso',
                'nome_completo' => null,
                'descricao' => 'Incluir Kit de Ítens para os presos.',
            ],
            [
                'id' => 25,
                'nome' => 'Alterar Kit Preso',
                'nome_completo' => null,
                'descricao' => 'Alterar quantidade ou adicionar ítens para o Kit de Ítens do preso.',
            ],
            [
                'id' => 26,
                'nome' => 'Excluir ítens Kit Preso',
                'nome_completo' => null,
                'descricao' => 'Excluir ítens do Kit de Ítens do preso.',
            ],
            [
                'id' => 27,
                'nome' => 'Excluir Kit Preso',
                'nome_completo' => null,
                'descricao' => 'Excluir Kit de Ítens entregue aos presos.',
            ],
            [
                'id' => 28,
                'nome' => 'Incluir Ítem Pertence',
                'nome_completo' => null,
                'descricao' => 'Incluir ítens à Listagem de Ítens do Pertence, gerenciado pela Inclusão.',
            ],
            [
                'id' => 29,
                'nome' => 'Alterar Ítem Pertence',
                'nome_completo' => null,
                'descricao' => 'Alterar ítens existentes da Listagem de Ítens do Pertence, gerenciado pela Inclusão.',
            ],
            [
                'id' => 30,
                'nome' => 'Excluir Ítem Pertence',
                'nome_completo' => null,
                'descricao' => 'Excluir ítens da Listagem de Ítens do Pertence, gerenciado pela Inclusão.',
            ],
            [
                'id' => 31,
                'nome' => 'Incluir Pertences Guardados',
                'nome_completo' => null,
                'descricao' => 'Incluir numeração de pertences guardados. Os pertences incluídos na chegada do preso não dependem de permissão, pois são feitos automaticamente.',
            ],
            [
                'id' => 32,
                'nome' => 'Alterar Pertences Guardados',
                'nome_completo' => null,
                'descricao' => 'Alterar Pertences Guardados, Descartar/Doar ou realizar retirada do pertence.',
            ],
            [
                'id' => 33,
                'nome' => 'Excluir Pertences Guardados',
                'nome_completo' => null,
                'descricao' => 'Excluir Pertences Guardados.',
            ],
            [
                'id' => 34,
                'nome' => 'Incluir Sedex Retidos',
                'nome_completo' => null,
                'descricao' => 'Incluir numeração de Sedex Retidos.',
            ],
            [
                'id' => 35,
                'nome' => 'Alterar Sedex Retidos',
                'nome_completo' => null,
                'descricao' => 'Alterar Sedex Retidos, Descartar/Doar ou realizar retirada do pertence.',
            ],
            [
                'id' => 36,
                'nome' => 'Excluir Sedex Retidos',
                'nome_completo' => null,
                'descricao' => 'Excluir Sedex Retidos.',
            ],
            [
                'id' => 37,
                'nome' => 'Incluir Ordem de Saída Apresentações',
                'nome_completo' => null,
                'descricao' => 'Incluir Ordem de Saída para Apresentações de Presos.',
            ],
            [
                'id' => 38,
                'nome' => 'Alterar Ordem de Saída Apresentações',
                'nome_completo' => null,
                'descricao' => 'Alterar Ordem de Saída para Apresentações de Presos.',
            ],
            [
                'id' => 39,
                'nome' => 'Excluir Ordem de Saída Apresentações',
                'nome_completo' => null,
                'descricao' => 'Excluir Ordem de Saída para Apresentações de Presos.',
            ],
            [
                'id' => 40,
                'nome' => 'Imprimir Ordem de Saída Apresentações',
                'nome_completo' => null,
                'descricao' => 'Imprimir Ordem de Saída de Apresentações de Presos.',
            ],
            [
                'id' => 41,
                'nome' => 'Imprimir Ofício Escolta',
                'nome_completo' => null,
                'descricao' => 'Imprimir Ofício de Escolta dos Presos para as Apresentações.',
            ],
            [
                'id' => 42,
                'nome' => 'Imprimir Ofício Apresentação',
                'nome_completo' => null,
                'descricao' => 'Imprimir Ofício de Apresentação do Preso nos locais.',
            ],
            [
                'id' => 43,
                'nome' => 'Incluir Ordem de Saída Transferência',
                'nome_completo' => null,
                'descricao' => 'Incluir Ordem de Saída para Transferência de Presos.',
            ],
            [
                'id' => 44,
                'nome' => 'Alterar Ordem de Saída Transferência',
                'nome_completo' => null,
                'descricao' => 'Alterar Ordem de Saída para Transferência de Presos.',
            ],
            [
                'id' => 45,
                'nome' => 'Excluir Ordem de Saída Transferência',
                'nome_completo' => null,
                'descricao' => 'Excluir Ordem de Saída para Transferência de Presos.',
            ],
            [
                'id' => 46,
                'nome' => 'Imprimir Ordem de Saída Transferência',
                'nome_completo' => null,
                'descricao' => 'Imprimir Ordem de Saída de Transferência de Presos.',
            ],
            [
                'id' => 47,
                'nome' => 'Imprimir Ofício Transferência',
                'nome_completo' => null,
                'descricao' => 'Imprimir Ofício de Transferência do Preso para as Unidades.',
            ],
            [
                'id' => 48,
                'nome' => 'Imprimir Relação Transferência',
                'nome_completo' => null,
                'descricao' => 'Imprimir Relação de Envio ou Recebimento de presos nas Transferências.',
            ],
            [
                'id' => 49,
                'nome' => 'Incluir Unidades Prisionais',
                'nome_completo' => null,
                'descricao' => 'Incluir Unidades Prisionais na relação de Unidades.',
            ],
            [
                'id' => 50,
                'nome' => 'Alterar Unidades Prisionais',
                'nome_completo' => null,
                'descricao' => 'Alterar Unidades Prisionais da relação de Unidades.',
            ],
            [
                'id' => 51,
                'nome' => 'Excluir Unidades Prisionais',
                'nome_completo' => null,
                'descricao' => 'Excluir Unidades Prisionais da relação de Unidades.',
            ],
            [
                'id' => 52,
                'nome' => 'Imprimir Ofício Escolta Transferência',
                'nome_completo' => null,
                'descricao' => 'Imprimir Ofício de Escolta dos Presos para as Transferências.',
            ],
            [
                'id' => 53,
                'nome' => 'D.D.C.S.D.',
                'nome_completo' => 'Diretor de Divisão do Centro de Segurança e Disciplina',
                'descricao' => 'Permissão total à todas áreas da responsabilidade da Disciplina e Segurança, podendo atribuir permissões para usuários neste seguimento.',
            ],
            [
                'id' => 54,
                'nome' => 'D.S.N.S. Turno I',
                'nome_completo' => 'Diretor de Setor do Núcleo de Segurança - Turno I',
                'descricao' => 'Gerenciar permissões do Núcleo de Segurança do Turno, atribuindo permissões para usuários neste seguimento.',
            ],
            [
                'id' => 55,
                'nome' => 'D.S.N.S. Turno II',
                'nome_completo' => 'Diretor de Setor do Núcleo de Segurança - Turno II',
                'descricao' => 'Gerenciar permissões do Núcleo de Segurança do Turno, atribuindo permissões para usuários neste seguimento.',
            ],
            [
                'id' => 56,
                'nome' => 'D.S.N.S. Turno III',
                'nome_completo' => 'Diretor de Setor do Núcleo de Segurança - Turno III',
                'descricao' => 'Gerenciar permissões do Núcleo de Segurança do Turno, atribuindo permissões para usuários neste seguimento.',
            ],
            [
                'id' => 57,
                'nome' => 'D.S.N.S. Turno IV',
                'nome_completo' => 'Diretor de Setor do Núcleo de Segurança - Turno IV',
                'descricao' => 'Gerenciar permissões do Núcleo de Segurança do Turno, atribuindo permissões para usuários neste seguimento.',
            ],
            [
                'id' => 58,
                'nome' => 'Penal Turno I',
                'nome_completo' => null,
                'descricao' => 'Funcionário Responsável pelo setor de Penal, podendo atribuir permissões para usuários neste seguimento.',
            ],
            [
                'id' => 59,
                'nome' => 'Penal Turno II',
                'nome_completo' => null,
                'descricao' => 'Funcionário Responsável pelo setor de Penal, podendo atribuir permissões para usuários neste seguimento.',
            ],
            [
                'id' => 60,
                'nome' => 'Penal Turno III',
                'nome_completo' => null,
                'descricao' => 'Funcionário Responsável pelo setor de Penal, podendo atribuir permissões para usuários neste seguimento.',
            ],
            [
                'id' => 61,
                'nome' => 'Penal Turno IV',
                'nome_completo' => null,
                'descricao' => 'Funcionário Responsável pelo setor de Penal, podendo atribuir permissões para usuários neste seguimento.',
            ],
            [
                'id' => 62,
                'nome' => 'Diretor Geral',
                'nome_completo' => 'Diretor Técnico III',
                'descricao' => 'Permissão total a todas áreas do Sistema, tendo como excessão às áreas do Desenvolvedor.',
            ],
            [
                'id' => 63,
                'nome' => 'Zelador Raio A',
                'nome_completo' => null,
                'descricao' => 'Permissão destinada para Zelador do Raio A.',
            ],
            [
                'id' => 64,
                'nome' => 'Zelador Raio B',
                'nome_completo' => null,
                'descricao' => 'Permissão destinada para Zelador do Raio B.',
            ],
            [
                'id' => 65,
                'nome' => 'Zelador Raio C',
                'nome_completo' => null,
                'descricao' => 'Permissão destinada para Zelador do Raio C.',
            ],
            [
                'id' => 66,
                'nome' => 'Zelador Raio D',
                'nome_completo' => null,
                'descricao' => 'Permissão destinada para Zelador do Raio D.',
            ],
            [
                'id' => 67,
                'nome' => 'Zelador Radial',
                'nome_completo' => null,
                'descricao' => 'Permissão destinada para Zelador da Radial.',
            ],
            [
                'id' => 68,
                'nome' => 'Zelador Inclusão + MPSP + Trabalho',
                'nome_completo' => null,
                'descricao' => 'Permissão destinada para Zelador da Inclusão + MPSP + Trabalho.',
            ],
            [
                'id' => 69,
                'nome' => 'Alterar listagem de Origem',
                'nome_completo' => null,
                'descricao' => 'Alterar listagem dos nomes das origens das inclusões.',
            ],
            [
                'id' => 70,
                'nome' => 'Excluir Origem de Inclusão',
                'nome_completo' => null,
                'descricao' => 'Excluir nome da listagem das origem das inclusões.',
            ],
            [
                'id' => 71,
                'nome' => 'Incluir Tipo de Documento',
                'nome_completo' => null,
                'descricao' => 'Incluir nome na listagem dos tipos de documentos.',
            ],
            [
                'id' => 72,
                'nome' => 'Alterar Tipo de Documento',
                'nome_completo' => null,
                'descricao' => 'Alterar nomes da listagem dos tipos de documentos.',
            ],
            [
                'id' => 73,
                'nome' => 'Excluir Tipo de Documento',
                'nome_completo' => null,
                'descricao' => 'Excluir nome da listagem dos tipos de documentos.',
            ],
            [
                'id' => 74,
                'nome' => 'Alterar Profissão',
                'nome_completo' => null,
                'descricao' => 'Alterar nome na listagem de profissões.',
            ],
            [
                'id' => 75,
                'nome' => 'Excluir Profissão',
                'nome_completo' => null,
                'descricao' => 'Excluir nome da listagem de profissões.',
            ],
            [
                'id' => 76,
                'nome' => 'Incluir Estado',
                'nome_completo' => null,
                'descricao' => 'Incluir nome na listagem dos Estados.',
            ],
            [
                'id' => 77,
                'nome' => 'Alterar Estado',
                'nome_completo' => null,
                'descricao' => 'Alterar nomes da listagem dos Estados.',
            ],
            [
                'id' => 78,
                'nome' => 'Excluir Estado',
                'nome_completo' => null,
                'descricao' => 'Excluir nome da listagem dos Estados.',
            ],
            [
                'id' => 79,
                'nome' => 'Incluir Nacionalidade',
                'nome_completo' => null,
                'descricao' => 'Incluir nome na listagem das Nacionalidades.',
            ],
            [
                'id' => 80,
                'nome' => 'Alterar Nacionalidade',
                'nome_completo' => null,
                'descricao' => 'Alterar nomes da listagem das Nacionalidades.',
            ],
            [
                'id' => 81,
                'nome' => 'Excluir Nacionalidade',
                'nome_completo' => null,
                'descricao' => 'Excluir nome da listagem das Nacionalidades.',
            ],
            [
                'id' => 82,
                'nome' => 'Incluir Gênero',
                'nome_completo' => null,
                'descricao' => 'Incluir nome na listagem das Gêneros.',
            ],
            [
                'id' => 83,
                'nome' => 'Alterar Gênero',
                'nome_completo' => null,
                'descricao' => 'Alterar nomes da listagem das Gêneros.',
            ],
            [
                'id' => 84,
                'nome' => 'Excluir Gênero',
                'nome_completo' => null,
                'descricao' => 'Excluir nome da listagem das Gêneros.',
            ],
        ];

        DB::table('ref_permissao')->insert($data);

    }
}
