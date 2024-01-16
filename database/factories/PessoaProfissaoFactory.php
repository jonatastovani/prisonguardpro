<?php

namespace Database\Factories;

use App\Models\Pessoa;
use App\Models\RefProfissao;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PessoaProfissao>
 */
class PessoaProfissaoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pessoa_id' => Pessoa::pluck('id')->random(),
            'profissao_id' => RefProfissao::pluck('id')->random(),
        ];
    }
}
