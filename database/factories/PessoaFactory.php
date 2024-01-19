<?php

namespace Database\Factories;

use App\Models\RefCidade;
use App\Models\RefEscolaridade;
use App\Models\RefEstadoCivil;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pessoa>
 */
class PessoaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->unique()->sentence(4),
            'mae' => $this->faker->unique()->sentence(4),
            'pai' => $this->faker->unique()->sentence(4),
            'data_nasc' => $this->faker->date(),
            'cidade_nasc_id' => RefCidade::pluck('id')->random(),
            'genero_id' => 1,
            'escolaridade_id' => RefEscolaridade::pluck('id')->random(),
            'estado_civil_id' => RefEstadoCivil::pluck('id')->random(),
            'created_user_id' => 1,
            'created_ip' => config('sistema.ipHost'),
            'created_at' => now(),
        ];
    }
}
