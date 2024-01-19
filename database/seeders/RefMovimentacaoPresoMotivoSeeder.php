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
            ['id' => 1, 'sigla' => 'ABS', 'nome' => 'Absolvição Processual', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 2, 'sigla' => 'AJU', 'nome' => 'Aguardar Julgamento', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 3, 'sigla' => 'AGR', 'nome' => 'Aguardar Remoção', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 4, 'sigla' => 'ALV', 'nome' => 'Alvará de Soltura', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 5, 'sigla' => 'AJ', 'nome' => 'Apresentação Judicial', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 6, 'sigla' => 'EME', 'nome' => 'Atendimento A Situações Emergenciais', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 7, 'sigla' => 'B', 'nome' => 'Concessão de Indulto', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 8, 'sigla' => 'F', 'nome' => 'Conversão Pena Privativa Liberdade em Restritiva Direitos', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 9, 'sigla' => 'G', 'nome' => 'Cancelamento Conversão Pena Privativa Liberdade em Restritiva de Direitos', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 10, 'sigla' => 'A', 'nome' => 'Cumprimento de Pena', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 11, 'sigla' => 'H', 'nome' => 'Cancelamento Conversão da Pena de Multa em Privativa de Liberdade', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 12, 'sigla' => 'COB', 'nome' => 'Consessão de Benefícios', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 13, 'sigla' => 'CHC', 'nome' => 'Concessão de Habeas Corpus', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 14, 'sigla' => 'CMS', 'nome' => 'Cumprir Medida de Segurança', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 15, 'sigla' => 'MS', 'nome' => 'Cumprimento da Medida de Segurança', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 16, 'sigla' => 'N', 'nome' => 'Desinternação Condicional', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 17, 'sigla' => 'L', 'nome' => 'Evasão', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 18, 'sigla' => 'EC', 'nome' => 'Exames Criminológicos', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 19, 'sigla' => 'O', 'nome' => 'Extinção da Punibilidade', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 20, 'sigla' => 'O1', 'nome' => 'Extinção da Punibilidade - Anistia', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 21, 'sigla' => 'O2', 'nome' => 'Extinção da Punibilidade - Graça ou Indulto Individual', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 22, 'sigla' => 'FA', 'nome' => 'Falecimento', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 23, 'sigla' => 'HDU', 'nome' => 'Homicídio Dentro da Unidade', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 24, 'sigla' => 'HFU', 'nome' => 'Homicídio Fora da Unidade', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 25, 'sigla' => 'INE', 'nome' => 'Inadaptabilidade No Atual Estabelecimento', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 26, 'sigla' => 'INT', 'nome' => 'Internação', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 27, 'sigla' => 'LPR', 'nome' => 'Liberdade Provisória', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 28, 'sigla' => 'D', 'nome' => 'Livramento Condicional', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 29, 'sigla' => 'NDU', 'nome' => 'Morte Natural Dentro da Unidade', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 30, 'sigla' => 'NFU', 'nome' => 'Morte Natural Fora da Unidade', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 31, 'sigla' => 'MTV', 'nome' => 'Mudança de Tipo de Vaga', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 32, 'sigla' => 'OC', 'nome' => 'Observação Criminológica', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 33, 'sigla' => 'PER', 'nome' => 'Permissão de Saída', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 34, 'sigla' => 'P', 'nome' => 'Prescrição Processual', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 35, 'sigla' => 'E', 'nome' => 'Prisão Albergue Domiciliar', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 36, 'sigla' => 'FLA', 'nome' => 'Prisão em Flagrante', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 37, 'sigla' => 'PP', 'nome' => 'Prisão Preventiva', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 38, 'sigla' => 'CMP', 'nome' => 'Problemas de Comportamento', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 39, 'sigla' => 'POE', 'nome' => 'Processo em Outro Estado', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 40, 'sigla' => 'PRE', 'nome' => 'Progressão de Regime', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 41, 'sigla' => 'FAM', 'nome' => 'Proximidade da Família', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 42, 'sigla' => 'REC', 'nome' => 'Recaptura', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 43, 'sigla' => 'RRE', 'nome' => 'Regressão de Regime', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 44, 'sigla' => 'I', 'nome' => 'Relaxamento de Flagrante', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 45, 'sigla' => 'Q', 'nome' => 'Remoção Para Unidade Fora da Coespe', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 46, 'sigla' => 'RVF', 'nome' => 'Remoção Para Unidade Fora do Estado ou País', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 47, 'sigla' => 'RP', 'nome' => 'Requisição Policial', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 48, 'sigla' => 'ESP', 'nome' => 'Retorno Espontâneo', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 49, 'sigla' => 'RDE', 'nome' => 'Revogação da Desinternação', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 50, 'sigla' => 'RLC', 'nome' => 'Revogação do Livramento Condicional', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 51, 'sigla' => 'RPA', 'nome' => 'Revogação da Prisão Albergue Domiciliar', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 52, 'sigla' => 'J', 'nome' => 'Revogação da Prisão Preventiva', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 53, 'sigla' => 'RSC', 'nome' => 'Revogação da Suspensão Condicional - Sursis', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 54, 'sigla' => 'ST', 'nome' => 'Saída Temporária', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 55, 'sigla' => 'SCR', 'nome' => 'Sentença Condenatória Recorrível', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 56, 'sigla' => 'SDU', 'nome' => 'Suicídio Dentro da Unidade', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 57, 'sigla' => 'SFU', 'nome' => 'Suicídio Fora da Unidade', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 58, 'sigla' => 'SC', 'nome' => 'Suspensão Condicional', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 59, 'sigla' => 'C', 'nome' => 'Suspensão Condicional - Sursis', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 60, 'sigla' => 'TRA', 'nome' => 'Transferência de Estabelecimento Penal', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 61, 'sigla' => 'TC', 'nome' => 'Tratamento Criminológico', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 62, 'sigla' => 'TS', 'nome' => 'Tratamento de Saúde', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 63, 'sigla' => 'OUT', 'nome' => 'Outros', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 64, 'sigla' => 'M', 'nome' => 'Abandono', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 65, 'sigla' => 'CM', 'nome' => 'Cumprir Medida de Segurança', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 68, 'sigla' => 'HO', 'nome' => 'Homicídio Dentro da Unidade', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 69, 'sigla' => 'ID', 'nome' => 'Concessão de Indulto', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 70, 'sigla' => 'LC', 'nome' => 'Livramento Condicional', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 71, 'sigla' => 'PA', 'nome' => 'Prisão Albergue Domiciliar', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 72, 'sigla' => 'PR', 'nome' => 'Progressão de Regime', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 73, 'sigla' => 'EV', 'nome' => 'Evasão', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 74, 'sigla' => 'TE', 'nome' => 'Trânsito Externo', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 75, 'sigla' => 'EP', 'nome' => 'Exclusão do Provisório', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 76, 'sigla' => 'AT', 'nome' => 'Aguardar Transferência', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 77, 'sigla' => 'PSC', 'nome' => 'Prestação de Serviço A Comunidade', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 78, 'sigla' => 'RRA', 'nome' => 'Restabelecimento do Regime Aberto', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id' => 79, 'sigla' => 'RLI', 'nome' => 'Restabelecimento do Livramento Condicional', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_movimentacao_preso_motivos')->insert($insert);
    }
}
