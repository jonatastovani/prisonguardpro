<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefMovimentacaoPresoMotivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['id' => 1, 'sigla' => 'ABS', 'nome' => 'Absolvição Processual', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 2, 'sigla' => 'AJU', 'nome' => 'Aguardar Julgamento', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 3, 'sigla' => 'AGR', 'nome' => 'Aguardar Remoção', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 4, 'sigla' => 'ALV', 'nome' => 'Alvará de Soltura', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 5, 'sigla' => 'AJ', 'nome' => 'Apresentação Judicial', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 6, 'sigla' => 'EME', 'nome' => 'Atendimento A Situações Emergenciais', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 7, 'sigla' => 'B', 'nome' => 'Concessão de Indulto', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 8, 'sigla' => 'F', 'nome' => 'Conversão Pena Privativa Liberdade em Restritiva Direitos', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 9, 'sigla' => 'G', 'nome' => 'Cancelamento Conversão Pena Privativa Liberdade em Restritiva de Direitos', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 10, 'sigla' => 'A', 'nome' => 'Cumprimento de Pena', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 11, 'sigla' => 'H', 'nome' => 'Cancelamento Conversão da Pena de Multa em Privativa de Liberdade', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 12, 'sigla' => 'COB', 'nome' => 'Consessão de Benefícios', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 13, 'sigla' => 'CHC', 'nome' => 'Concessão de Habeas Corpus', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 14, 'sigla' => 'CMS', 'nome' => 'Cumprir Medida de Segurança', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 15, 'sigla' => 'MS', 'nome' => 'Cumprimento da Medida de Segurança', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 16, 'sigla' => 'N', 'nome' => 'Desinternação Condicional', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 17, 'sigla' => 'L', 'nome' => 'Evasão', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 18, 'sigla' => 'EC', 'nome' => 'Exames Criminológicos', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 19, 'sigla' => 'O', 'nome' => 'Extinção da Punibilidade', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 20, 'sigla' => 'O1', 'nome' => 'Extinção da Punibilidade - Anistia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 21, 'sigla' => 'O2', 'nome' => 'Extinção da Punibilidade - Graça ou Indulto Individual', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 22, 'sigla' => 'FA', 'nome' => 'Falecimento', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 23, 'sigla' => 'HDU', 'nome' => 'Homicídio Dentro da Unidade', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 24, 'sigla' => 'HFU', 'nome' => 'Homicídio Fora da Unidade', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 25, 'sigla' => 'INE', 'nome' => 'Inadaptabilidade No Atual Estabelecimento', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 26, 'sigla' => 'INT', 'nome' => 'Internação', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 27, 'sigla' => 'LPR', 'nome' => 'Liberdade Provisória', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 28, 'sigla' => 'D', 'nome' => 'Livramento Condicional', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 29, 'sigla' => 'NDU', 'nome' => 'Morte Natural Dentro da Unidade', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 30, 'sigla' => 'NFU', 'nome' => 'Morte Natural Fora da Unidade', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 31, 'sigla' => 'MTV', 'nome' => 'Mudança de Tipo de Vaga', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 32, 'sigla' => 'OC', 'nome' => 'Observação Criminológica', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 33, 'sigla' => 'PER', 'nome' => 'Permissão de Saída', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 34, 'sigla' => 'P', 'nome' => 'Prescrição Processual', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 35, 'sigla' => 'E', 'nome' => 'Prisão Albergue Domiciliar', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 36, 'sigla' => 'FLA', 'nome' => 'Prisão em Flagrante', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 37, 'sigla' => 'PP', 'nome' => 'Prisão Preventiva', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 38, 'sigla' => 'CMP', 'nome' => 'Problemas de Comportamento', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 39, 'sigla' => 'POE', 'nome' => 'Processo em Outro Estado', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 40, 'sigla' => 'PRE', 'nome' => 'Progressão de Regime', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 41, 'sigla' => 'FAM', 'nome' => 'Proximidade da Família', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 42, 'sigla' => 'REC', 'nome' => 'Recaptura', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 43, 'sigla' => 'RRE', 'nome' => 'Regressão de Regime', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 44, 'sigla' => 'I', 'nome' => 'Relaxamento de Flagrante', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 45, 'sigla' => 'Q', 'nome' => 'Remoção Para Unidade Fora da Coespe', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 46, 'sigla' => 'RVF', 'nome' => 'Remoção Para Unidade Fora do Estado ou País', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 47, 'sigla' => 'RP', 'nome' => 'Requisição Policial', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 48, 'sigla' => 'ESP', 'nome' => 'Retorno Espontâneo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 49, 'sigla' => 'RDE', 'nome' => 'Revogação da Desinternação', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 50, 'sigla' => 'RLC', 'nome' => 'Revogação do Livramento Condicional', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 51, 'sigla' => 'RPA', 'nome' => 'Revogação da Prisão Albergue Domiciliar', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 52, 'sigla' => 'J', 'nome' => 'Revogação da Prisão Preventiva', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 53, 'sigla' => 'RSC', 'nome' => 'Revogação da Suspensão Condicional - Sursis', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 54, 'sigla' => 'ST', 'nome' => 'Saída Temporária', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 55, 'sigla' => 'SCR', 'nome' => 'Sentença Condenatória Recorrível', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 56, 'sigla' => 'SDU', 'nome' => 'Suicídio Dentro da Unidade', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 57, 'sigla' => 'SFU', 'nome' => 'Suicídio Fora da Unidade', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 58, 'sigla' => 'SC', 'nome' => 'Suspensão Condicional', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 59, 'sigla' => 'C', 'nome' => 'Suspensão Condicional - Sursis', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 60, 'sigla' => 'TRA', 'nome' => 'Transferência de Estabelecimento Penal', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 61, 'sigla' => 'TC', 'nome' => 'Tratamento Criminológico', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 62, 'sigla' => 'TS', 'nome' => 'Tratamento de Saúde', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 63, 'sigla' => 'OUT', 'nome' => 'Outros', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 64, 'sigla' => 'M', 'nome' => 'Abandono', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 65, 'sigla' => 'CM', 'nome' => 'Cumprir Medida de Segurança', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 68, 'sigla' => 'HO', 'nome' => 'Homicídio Dentro da Unidade', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 69, 'sigla' => 'ID', 'nome' => 'Concessão de Indulto', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 70, 'sigla' => 'LC', 'nome' => 'Livramento Condicional', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 71, 'sigla' => 'PA', 'nome' => 'Prisão Albergue Domiciliar', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 72, 'sigla' => 'PR', 'nome' => 'Progressão de Regime', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 73, 'sigla' => 'EV', 'nome' => 'Evasão', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 74, 'sigla' => 'TE', 'nome' => 'Trânsito Externo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 75, 'sigla' => 'EP', 'nome' => 'Exclusão do Provisório', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 76, 'sigla' => 'AT', 'nome' => 'Aguardar Transferência', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 77, 'sigla' => 'PSC', 'nome' => 'Prestação de Serviço A Comunidade', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 78, 'sigla' => 'RRA', 'nome' => 'Restabelecimento do Regime Aberto', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 79, 'sigla' => 'RLI', 'nome' => 'Restabelecimento do Livramento Condicional', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_movimentacao_preso_motivos')->insert($insert);
    }
}
