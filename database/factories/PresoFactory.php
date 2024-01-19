<?php

namespace Database\Factories;

use App\Common\FuncoesPresos;
use App\Models\Pessoa;
use App\Models\RefCabeloCor;
use App\Models\RefCabeloTipo;
use App\Models\RefCrenca;
use App\Models\RefCutis;
use App\Models\RefOlhoCor;
use App\Models\RefOlhoTipo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Preso>
 */
class PresoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $matricula = $this->faker->unique()->numberBetween(1000, 1500000);
        $digito = FuncoesPresos::retornaDigitoMatricula($matricula);
        return [
            'pessoa_id' => Pessoa::pluck('id')->unique()->random(),
            'matricula' => $matricula.$digito,
            'estatura' => $this->faker->randomFloat(2, 1.5, 1.95),
            'peso' => $this->faker->randomFloat(1, 60, 130),
            'cutis_id' => RefCutis::pluck('id')->random(),
            'cabelo_tipo_id' => RefCabeloTipo::pluck('id')->random(),
            'cabelo_cor_id' => RefCabeloCor::pluck('id')->random(),
            'olho_cor_id' => RefOlhoCor::pluck('id')->random(),
            'olho_tipo_id' => RefOlhoTipo::pluck('id')->random(),
            'crenca_id' => RefCrenca::pluck('id')->random(),
            'sinais' => $this->faker->paragraph(),
            'created_user_id' => 1,
            'created_ip' => config('sistema.ipHost'),
            'created_at' => now(),
        ];
    }
}
