<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::create([
        //     'nome' => 'Admin',
        //     'username' => 'admin',
        //     'cpf' => 0,
        //     'password' => bcrypt('password')
        // ]);

        $insert = [
            ['nome' => 'Admin', 'username' => 'admin', 'cpf' => 0, 'password' => bcrypt('password'), 'created_at' => now()],
            ['nome' => 'Teste', 'username' => 'teste', 'cpf' => 123, 'password' => bcrypt('password'), 'created_at' => now()],
        ];

        DB::table('users')->insert($insert);

    }
}
