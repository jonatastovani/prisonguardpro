<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            RefNacionalidadeSeeder::class,
            RefEstadoSeeder::class,
            RefCabeloCorSeeder::class,
            RefCabeloTipoSeeder::class,
            RefCutisSeeder::class,
            RefEscolaridadeSeeder::class,
            RefEstadoCivilSeeder::class,
            RefOlhoCorSeeder::class,
            RefOlhoTipoSeeder::class,
            RefCrencaSeeder::class,
            RefArtigoSeeder::class,
            RefTurnoSeeder::class,
            RefTurnoSeguinteSeeder::class,
            RefTurnoTipoPermissaoSeeder::class,
            RefPermissaoGrupoSeeder::class,
            RefPermissaoSeeder::class,
            UserPermissaoSeeder::class,
            RefTurnoPermissaoSeeder::class,
            RefPermissaoConfigSeeder::class,
            RefMovimentacaoPresoTipoSeeder::class,
            RefMovimentacaoPresoTipoConfigSeeder::class,
            RefMovimentacaoPresoMotivoSeeder::class,
            RefMovimentacaoPresoSeeder::class,
            RefIncOrigemSeeder::class,
            RefDocumentoTipoSeeder::class,
            RefProfissaoSeeder::class,
            RefGeneroSeeder::class,
            RefCidadeSeeder::class,
            RefDocumentoOrgaoEmissorSeeder::class,
            RefStatusTipoSeeder::class,
            RefStatusNomeSeeder::class,
            RefStatusSeeder::class,
            RefCoresSeeder::class,
            RefPresoConvivioTipoSeeder::class,
            // PessoaSeeder::class, // Faker
            // PessoaDocumentoSeeder::class, // Faker
            // PresoSeeder::class, // Faker
            // PessoaProfissaoSeeder::class, // Faker
        ]);
    }
}
