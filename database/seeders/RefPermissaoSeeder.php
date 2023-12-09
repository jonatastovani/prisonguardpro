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
        $insert = [
            ['nome' => 'Desenvolvedor', 'nome_completo' => null, 'descricao' => 'Permissão máxima para todas áreas do Sistema.', 'diretor_bln' => false, 'permissao_pai_id' => null, 'grupo_pai_id' => null, 'grupo_id' => null, 'ordem' => null],
            ['nome' => 'Administrador Sistema', 'nome_completo' => null, 'descricao' => 'Permissão total a todas áreas do Sistema, tendo como excessão às áreas do Desenvolvedor.', 'diretor_bln' => false, 'permissao_pai_id' => 1, 'grupo_pai_id' => null, 'grupo_id' => 1, 'ordem' => null],
            ['nome' => 'D.N.I', 'nome_completo' => null, 'descricao' => 'Diretor do Núcleo de Inclusão. Permissão total à todas áreas da Inclusão, podendo atribuir permissões para usuários neste setor.', 'diretor_bln' => true, 'permissao_pai_id' => null, 'grupo_pai_id' => null, 'grupo_id' => 4, 'ordem' => null],
            ['nome' => 'Incluir Presos Inclusão', 'nome_completo' => null, 'descricao' => 'Incluir presos no setor de Inclusão.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 5, 'ordem' => 3],
            ['nome' => 'Alterar Presos Inclusão', 'nome_completo' => null, 'descricao' => 'Alterar presos no setor de Inclusão.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 5, 'ordem' => 4],
            ['nome' => 'Excluir Presos Inclusão', 'nome_completo' => null, 'descricao' => 'Excluir presos no setor de Inclusão.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 5, 'ordem' => 5],
            ['nome' => 'Imprimir Entrada de Presos', 'nome_completo' => null, 'descricao' => 'Imprimir as entradas de preso do setor de Inclusão', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 5, 'ordem' => 1],
            ['nome' => 'Imprimir Ficha de Digitais de Presos', 'nome_completo' => null, 'descricao' => 'Imprimir as fichas para colher papiloscopia dos presos', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 5, 'ordem' => 2],
            ['nome' => 'Diretor CIMIC', 'nome_completo' => 'Diretor Técnico II - CIMIC', 'descricao' => 'Permissão total à todas áreas do CIMIC, podendo atribuir permissões para usuários neste setor.', 'diretor_bln' => true, 'permissao_pai_id' => null, 'grupo_pai_id' => null, 'grupo_id' => 3, 'ordem' => null],
            ['nome' => 'Alterar Qualificativa de Preso', 'nome_completo' => null, 'descricao' => 'Alterar as informações da Qualificativa do Preso.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 6, 'ordem' => 2],
            ['nome' => 'Incluir Presos na Unidade', 'nome_completo' => null, 'descricao' => 'Incluir presos na Unidade após ser lançado incluso pela Inclusão.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 11, 'ordem' => 2],
            ['nome' => 'Alterar Qualificativa de Preso', 'nome_completo' => null, 'descricao' => 'Alterar as informações da Qualificativa do Preso.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 11, 'ordem' => 3],
            ['nome' => 'Incluir Movimentações de Preso', 'nome_completo' => null, 'descricao' => 'Incluir Movimentações de Presos.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => null, 'ordem' => null],
            ['nome' => 'Alterar Movimentações de Preso', 'nome_completo' => null, 'descricao' => 'Alterar Movimentações de Presos.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => null, 'ordem' => null],
            ['nome' => 'Excluir Movimentações de Preso', 'nome_completo' => null, 'descricao' => 'Excluir Movimentações de Presos.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => null, 'ordem' => null],
            ['nome' => 'Incluir Presos Inclusão', 'nome_completo' => null, 'descricao' => 'Incluir presos no setor de Inclusão.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 12, 'ordem' => 1],
            ['nome' => 'Alterar Presos Inclusão', 'nome_completo' => null, 'descricao' => 'Alterar presos no setor de Inclusão.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 12, 'ordem' => 2],
            ['nome' => 'Excluir Presos Inclusão', 'nome_completo' => null, 'descricao' => 'Excluir presos no setor de Inclusão.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 12, 'ordem' => 3],
            ['nome' => 'Alterar listagem de Artigos', 'nome_completo' => null, 'descricao' => 'Alterar ítens da listagem personalizada de Artigos.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 13, 'ordem' => 1],
            ['nome' => 'Excluir listagem de Artigos', 'nome_completo' => null, 'descricao' => 'Excluir ítens da listagem personalizada de Artigos.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 13, 'ordem' => 2],
            ['nome' => 'Imprimir Termo de Abertura', 'nome_completo' => null, 'descricao' => 'Imprimir Termo de Abertura das Inclusões do Preso', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 11, 'ordem' => 1],
            ['nome' => 'Excluir Entrada de Presos', 'nome_completo' => null, 'descricao' => 'Excluir a Entrada de Presos e todos os presos inclusos.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 5, 'ordem' => 6],
            ['nome' => 'Imprimir Termo de Declaração', 'nome_completo' => null, 'descricao' => 'Imprimir Termo de Declaração Integridade Física.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 6, 'ordem' => 1],
            ['nome' => 'Incluir Kit Preso', 'nome_completo' => null, 'descricao' => 'Incluir Kit de Ítens para os presos.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 7, 'ordem' => 1],
            ['nome' => 'Alterar Kit Preso', 'nome_completo' => null, 'descricao' => 'Alterar quantidade ou adicionar ítens para o Kit de Ítens do presos.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 7, 'ordem' => 2],
            ['nome' => 'Excluir ítens Kit Preso', 'nome_completo' => null, 'descricao' => 'Excluir ítens do Kit de Ítens do presos.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 7, 'ordem' => 3],
            ['nome' => 'Excluir Kit Preso', 'nome_completo' => null, 'descricao' => 'Excluir Kit de Ítens entregue aos presos.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 7, 'ordem' => 4],
            ['nome' => 'Incluir Ítem Pertence', 'nome_completo' => null, 'descricao' => 'Incluir ítens à Listagem de Ítens do Pertence, gerenciado pela Inclusão.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 8, 'ordem' => 1],
            ['nome' => 'Alterar Ítem Pertence', 'nome_completo' => null, 'descricao' => 'Alterar ítens existentes da Listagem de Ítens do Pertence, gerenciado pela Inclusão.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 8, 'ordem' => 2],
            ['nome' => 'Excluir Ítem Pertence', 'nome_completo' => null, 'descricao' => 'Excluir ítens da Listagem de Ítens do Pertence, gerenciado pela Inclusão.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 8, 'ordem' => 3],
            ['nome' => 'Incluir Pertences Guardados', 'nome_completo' => null, 'descricao' => 'Incluir numeração de pertences guardados. Os pertences incluídos na chegada do preso não dependem de permissão, pois são feitos automaticamente.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 9, 'ordem' => 1],
            ['nome' => 'Alterar Pertences Guardados', 'nome_completo' => null, 'descricao' => 'Alterar Pertences Guardados, Descartar/Doar ou realizar retirada do pertence.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 9, 'ordem' => 2],
            ['nome' => 'Excluir Pertences Guardados', 'nome_completo' => null, 'descricao' => 'Excluir Pertences Guardados.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 9, 'ordem' => 3],
            ['nome' => 'Incluir Sedex Retidos', 'nome_completo' => null, 'descricao' => 'Incluir numeração de Sedex Retidos.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 10, 'ordem' => 1],
            ['nome' => 'Alterar Sedex Retidos', 'nome_completo' => null, 'descricao' => 'Alterar Sedex Retidos, Descartar/Doar ou realizar retirada do pertence.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 10, 'ordem' => 2],
            ['nome' => 'Excluir Sedex Retidos', 'nome_completo' => null, 'descricao' => 'Excluir Sedex Retidos.', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => null, 'grupo_id' => 10, 'ordem' => 3],
            ['nome' => 'Incluir Ordem de Saída Apresentações', 'nome_completo' => null, 'descricao' => 'Incluir Ordem de Saída para Apresentações de Presos.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 14, 'ordem' => 4],
            ['nome' => 'Alterar Ordem de Saída Apresentações', 'nome_completo' => null, 'descricao' => 'Alterar Ordem de Saída para Apresentações de Presos.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 14, 'ordem' => 5],
            ['nome' => 'Excluir Ordem de Saída Apresentações', 'nome_completo' => null, 'descricao' => 'Excluir Ordem de Saída para Apresentações de Presos.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 14, 'ordem' => 6],
            ['nome' => 'Imprimir Ordem de Saída Apresentações', 'nome_completo' => null, 'descricao' => 'Imprimir Ordem de Saída de Apresentações de Presos.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 14, 'ordem' => 2],
            ['nome' => 'Imprimir Ofício Escolta', 'nome_completo' => null, 'descricao' => 'Imprimir Ofício de Escolta dos Presos para as Apresentações.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 14, 'ordem' => 3],
            ['nome' => 'Imprimir Ofício Apresentação', 'nome_completo' => null, 'descricao' => 'Imprimir Ofício de Apresentação do Preso nos locais.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 14, 'ordem' => 1],
            ['nome' => 'Incluir Ordem de Saída Transferência', 'nome_completo' => null, 'descricao' => 'Incluir Ordem de Saída para Transferência de Presos.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 15, 'ordem' => 5],
            ['nome' => 'Alterar Ordem de Saída Transferência', 'nome_completo' => null, 'descricao' => 'Alterar Ordem de Saída para Transferência de Presos.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 15, 'ordem' => 6],
            ['nome' => 'Excluir Ordem de Saída Transferência', 'nome_completo' => null, 'descricao' => 'Excluir Ordem de Saída para Transferência de Presos.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 15, 'ordem' => 7],
            ['nome' => 'Imprimir Ordem de Saída Transferência', 'nome_completo' => null, 'descricao' => 'Imprimir Ordem de Saída de Transferência de Presos.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 15, 'ordem' => 3],
            ['nome' => 'Imprimir Ofício Transferência', 'nome_completo' => null, 'descricao' => 'Imprimir Ofício de Transferência do Preso para as Unidades.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 15, 'ordem' => 2],
            ['nome' => 'Imprimir Relação Transferência', 'nome_completo' => null, 'descricao' => 'Imprimir Relação de Envio ou Recebimento de presos nas Transferências.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 15, 'ordem' => 1],
            ['nome' => 'Incluir Unidades Prisionais', 'nome_completo' => null, 'descricao' => 'Incluir Unidades Prisionais na relação de Unidades.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 16, 'ordem' => 1],
            ['nome' => 'Alterar Unidades Prisionais', 'nome_completo' => null, 'descricao' => 'Alterar Unidades Prisionais da relação de Unidades.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 16, 'ordem' => 2],
            ['nome' => 'Excluir Unidades Prisionais', 'nome_completo' => null, 'descricao' => 'Excluir Unidades Prisionais da relação de Unidades.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 16, 'ordem' => 3],
            ['nome' => 'Imprimir Ofício Escolta Transferência', 'nome_completo' => null, 'descricao' => 'Imprimir Ofício de Escolta dos Presos para as Transferências.', 'diretor_bln' => false, 'permissao_pai_id' => 9, 'grupo_pai_id' => null, 'grupo_id' => 15, 'ordem' => 4],
            ['nome' => 'D.D.C.S.D.', 'nome_completo' => 'Diretor de Divisão do Centro de Segurança e Disciplina', 'descricao' => 'Permissão total à todas áreas da responsabilidade da Disciplina e Segurança, podendo atribuir permissões para usuários neste seguimento.', 'diretor_bln' => true, 'permissao_pai_id' => 1, 'grupo_pai_id' => null, 'grupo_id' => 3, 'ordem' => null],
            ['nome' => 'D.S.N.S. Turno I', 'nome_completo' => 'Diretor de Setor do Núcleo de Segurança - Turno I', 'descricao' => 'Gerenciar permissões do Núcleo de Segurança do Turno, atribuindo permissões para usuários neste seguimento.', 'diretor_bln' => true, 'permissao_pai_id' => 1, 'grupo_pai_id' => null, 'grupo_id' => 3, 'ordem' => null],
            ['nome' => 'D.S.N.S. Turno II', 'nome_completo' => 'Diretor de Setor do Núcleo de Segurança - Turno II', 'descricao' => 'Gerenciar permissões do Núcleo de Segurança do Turno, atribuindo permissões para usuários neste seguimento.', 'diretor_bln' => true, 'permissao_pai_id' => 1, 'grupo_pai_id' => null, 'grupo_id' => 3, 'ordem' => null],
            ['nome' => 'D.S.N.S. Turno III', 'nome_completo' => 'Diretor de Setor do Núcleo de Segurança - Turno III', 'descricao' => 'Gerenciar permissões do Núcleo de Segurança do Turno, atribuindo permissões para usuários neste seguimento.', 'diretor_bln' => true, 'permissao_pai_id' => 1, 'grupo_pai_id' => null, 'grupo_id' => 3, 'ordem' => null],
            ['nome' => 'D.S.N.S. Turno IV', 'nome_completo' => 'Diretor de Setor do Núcleo de Segurança - Turno IV', 'descricao' => 'Gerenciar permissões do Núcleo de Segurança do Turno, atribuindo permissões para usuários neste seguimento.', 'diretor_bln' => true, 'permissao_pai_id' => 1, 'grupo_pai_id' => null, 'grupo_id' => 3, 'ordem' => null],
            ['nome' => 'Penal Turno I', 'nome_completo' => null, 'descricao' => 'Funcionário Responsável pelo setor de Penal, podendo atribuir permissões para usuários neste seguimento.', 'diretor_bln' => false, 'permissao_pai_id' => 54, 'grupo_pai_id' => null, 'grupo_id' => 17, 'ordem' => null],
            ['nome' => 'Penal Turno II', 'nome_completo' => null, 'descricao' => 'Funcionário Responsável pelo setor de Penal, podendo atribuir permissões para usuários neste seguimento.', 'diretor_bln' => false, 'permissao_pai_id' => 55, 'grupo_pai_id' => null, 'grupo_id' => 17, 'ordem' => null],
            ['nome' => 'Penal Turno III', 'nome_completo' => null, 'descricao' => 'Funcionário Responsável pelo setor de Penal, podendo atribuir permissões para usuários neste seguimento.', 'diretor_bln' => false, 'permissao_pai_id' => 56, 'grupo_pai_id' => null, 'grupo_id' => 17, 'ordem' => null],
            ['nome' => 'Penal Turno IV', 'nome_completo' => null, 'descricao' => 'Funcionário Responsável pelo setor de Penal, podendo atribuir permissões para usuários neste seguimento.', 'diretor_bln' => false, 'permissao_pai_id' => 57, 'grupo_pai_id' => null, 'grupo_id' => 17, 'ordem' => null],
            ['nome' => 'Diretor Geral', 'nome_completo' => 'Diretor Técnico III', 'descricao' => 'Permissão total a todas áreas do Sistema, tendo como excessão às áreas do Desenvolvedor.', 'diretor_bln' => true, 'permissao_pai_id' => 2, 'grupo_pai_id' => null, 'grupo_id' => 2, 'ordem' => null],
            ['nome' => 'Zelador Raio A', 'nome_completo' => null, 'descricao' => 'Permissão destinada para Zelador do Raio A', 'diretor_bln' => false, 'permissao_pai_id' => null, 'grupo_pai_id' => 17, 'grupo_id' => 18, 'ordem' => 1],
            ['nome' => 'Zelador Raio B', 'nome_completo' => null, 'descricao' => 'Permissão destinada para Zelador do Raio B', 'diretor_bln' => false, 'permissao_pai_id' => null, 'grupo_pai_id' => 17, 'grupo_id' => 18, 'ordem' => 2],
            ['nome' => 'Zelador Raio C', 'nome_completo' => null, 'descricao' => 'Permissão destinada para Zelador do Raio C', 'diretor_bln' => false, 'permissao_pai_id' => null, 'grupo_pai_id' => 17, 'grupo_id' => 18, 'ordem' => 3],
            ['nome' => 'Zelador Raio D', 'nome_completo' => null, 'descricao' => 'Permissão destinada para Zelador do Raio D', 'diretor_bln' => false, 'permissao_pai_id' => null, 'grupo_pai_id' => 17, 'grupo_id' => 18, 'ordem' => 4],
            ['nome' => 'Zelador Radial', 'nome_completo' => null, 'descricao' => 'Permissão destinada para Zelador da Radial', 'diretor_bln' => false, 'permissao_pai_id' => null, 'grupo_pai_id' => 17, 'grupo_id' => 18, 'ordem' => 5],
            ['nome' => 'Zelador Inclusão + MPSP + Trabalho', 'nome_completo' => null, 'descricao' => 'Permissão destinada para Zelador da Inclusão + MPSP + Trabalho', 'diretor_bln' => false, 'permissao_pai_id' => 3, 'grupo_pai_id' => 17, 'grupo_id' => 18, 'ordem' => 6],        
            ];

        DB::table('ref_permissao')->insert($insert);

    }
}
