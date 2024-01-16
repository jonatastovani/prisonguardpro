<?php

namespace Database\Factories;

use App\Models\Pessoa;
use App\Models\RefDocumentoOrgaoEmissor;
use App\Models\RefDocumentoTipo;
use App\Models\RefEstado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PessoaDocumento>
 */
class PessoaDocumentoFactory extends Factory
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
            'doc_tipo_id' => RefDocumentoTipo::pluck('id')->random(),
            'org_exp_id' => RefDocumentoOrgaoEmissor::pluck('id')->random(),
            'estado_id' => RefEstado::pluck('id')->random(),
            'numero' => $this->faker->numberBetween(1000000,999999999),
            'id_user_created' => 1, 
            'ip_created' => config('sistema.ipHost'),
            'created_at' => now(),
        ];
    }
}
