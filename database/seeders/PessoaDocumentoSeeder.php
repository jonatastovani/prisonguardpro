<?php

namespace Database\Seeders;

use App\Models\PessoaDocumento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PessoaDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PessoaDocumento::factory(20)->create();
    }
}
