<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefCoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['id'=> 1,'nome' => 'Vermelho', 'cor_fundo' => '#FF0000', 'cor_texto' => '#FFFFFF', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 2,'nome' => 'Verde', 'cor_fundo' => '#00FF00', 'cor_texto' => '#000000', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 3,'nome' => 'Azul', 'cor_fundo' => '#0000FF', 'cor_texto' => '#FFFFFF', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 4,'nome' => 'Amarelo', 'cor_fundo' => '#FFFF00', 'cor_texto' => '#000000', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 5,'nome' => 'Rosa', 'cor_fundo' => '#FFC0CB', 'cor_texto' => '#000000', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 6,'nome' => 'Roxo', 'cor_fundo' => '#800080', 'cor_texto' => '#FFFFFF', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 7,'nome' => 'Laranja', 'cor_fundo' => '#FFA500', 'cor_texto' => '#000000', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 8,'nome' => 'Marrom', 'cor_fundo' => '#A52A2A', 'cor_texto' => '#FFFFFF', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 9,'nome' => 'Cinza', 'cor_fundo' => '#808080', 'cor_texto' => '#000000', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 10,'nome' => 'Preto', 'cor_fundo' => '#000000', 'cor_texto' => '#FFFFFF', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 11,'nome' => 'Turquesa', 'cor_fundo' => '#40E0D0', 'cor_texto' => '#000000', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 12,'nome' => 'Índigo', 'cor_fundo' => '#4B0082', 'cor_texto' => '#FFFFFF', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 13,'nome' => 'Ouro', 'cor_fundo' => '#FFD700', 'cor_texto' => '#000000', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 14,'nome' => 'Prata', 'cor_fundo' => '#C0C0C0', 'cor_texto' => '#000000', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 15,'nome' => 'Verde Limão', 'cor_fundo' => '#00FF00', 'cor_texto' => '#000000', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 16,'nome' => 'Azul Royal', 'cor_fundo' => '#4169E1', 'cor_texto' => '#FFFFFF', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 17,'nome' => 'Tomate', 'cor_fundo' => '#FF6347', 'cor_texto' => '#000000', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 18,'nome' => 'Magenta', 'cor_fundo' => '#FF00FF', 'cor_texto' => '#000000', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 19,'nome' => 'Verde Oliva', 'cor_fundo' => '#808000', 'cor_texto' => '#FFFFFF', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
            ['id'=> 20,'nome' => 'Ciano', 'cor_fundo' => '#00FFFF', 'cor_texto' => '#000000', 'created_user_id' => 1, 'created_ip' => $iplocal, 'created_at' => now()],
        ];
        
        DB::table('ref_cores')->insert($insert);

    }
}
