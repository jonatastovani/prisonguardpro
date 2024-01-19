<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefOlhoTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['nome' => 'Normal', 'descricao' => 'Ambos os olhos da pessoa são normais.', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Estrabismo convergente', 'descricao' => 'Desvio dos olhos para dentro.', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Estrabismo divergente', 'descricao' => 'Desvio dos olhos para fora.', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Estrabismo vertical', 'descricao' => 'Desvio vertical dos olhos.', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Visão Monocular Direita', 'descricao' => 'Cegueira total do olho esquerdo.', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Visão Monocular Esquerda', 'descricao' => 'Cegueira total do olho direito.', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Visão Dupla', 'descricao' => 'A pessoa enxerga uma imagem duplicada devido a problemas de alinhamento ou foco.', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Anoftalmia olho direito', 'descricao' => 'Ausência completa do olho direito.', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Anoftalmia olho esquerdo', 'descricao' => 'Ausência completa do olho esquerdo.', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Microftalmia', 'descricao' => 'Olho anormalmente pequeno.', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Exoftalmia', 'descricao' => 'Protrusão anormal do olho.', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Enoftalmia', 'descricao' => 'Envolvimento anormalmente profundo do olho na órbita.', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Heterocromia', 'descricao' => 'Diferença na cor dos olhos.', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Coloboma', 'descricao' => 'Ausência de uma parte do olho, como íris, retina ou pálpebra.', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Nistagmo', 'descricao' => 'Movimentos oculares involuntários e rítmicos.', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['nome' => 'Nevo Coroideano', 'descricao' => 'Mancha pigmentada na camada vascular do olho.', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];

        DB::table('ref_olho_tipos')->insert($insert);
    }
}
