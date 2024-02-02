<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefPresoConvivioTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['id'=>"1", 'nome' => 'Convívio normal', 'descricao' => 'Preso com convívio normal com a população da Unidade.', 'cor_id'=> null, 'convivio_padrao_bln' => true, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=>"2", 'nome' => 'Seguro', 'descricao' => 'Preso que necessita da M.P.S.P..', 'cor_id'=>"8", 'convivio_padrao_bln' => false, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=>"3", 'nome' => 'Seguro Artigo', 'descricao' => 'Preso que necessita de M.P.S.P. devido ao crime.', 'cor_id'=>"1", 'convivio_padrao_bln' => false, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=>"4", 'nome' => 'Facção Oposta', 'descricao' => 'Preso que necessita de isolamento devido ser pertencente a facção oposta aos custodiados da Unidade.', 'cor_id'=>"12", 'convivio_padrao_bln' => false, 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_preso_convivio_tipos')->insert($insert);

    }
}
