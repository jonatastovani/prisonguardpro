<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\RefCoresCabelo;
use App\Models\RefCrenca;
use App\Models\RefEscolaridade;
use App\Models\RefEstadoCivil;
use App\Models\RefOlho;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            UsersSeeder::class,
            // CategoriasSeeders::class,
            // ProdutoSeeder::class,
            RefNacionalidadeSeeder::class,
            RefEstadoSeeder::class,
            RefCoresCabeloSeeder::class,
            RefTiposCabeloSeeder::class,
            RefCutisSeeder::class,
            RefEscolaridadeSeeder::class,
            RefEstadoCivilSeeder::class,
            RefOlhoSeeder::class,
            RefCrencaSeeder::class,
            RefArtigoSeeder::class,
            RefTurnoSeeder::class,
            RefTurnoSeguinteSeeder::class,
            RefTurnoTipoPermissaoSeeder::class,
            RefPermissaoGrupoSeeder::class,
            RefPermissaoSeeder::class,
            RefTurnoPermissaoSeeder::class,
            RefPermissaoConfigSeeder::class,
            RefTipoMovimentacaoPresoSeeder::class,
            IncEntradaOrigemSeeder::class,
        ]);
    }
}
