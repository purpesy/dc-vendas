<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Venda;
use App\Models\Cliente;
use App\Models\Vendedor;

class VendaFactory extends Factory
{
    protected $model = Venda::class;

    public function definition()
    {
        return [
            'vendedor_id' => Vendedor::inRandomOrder()->first()->id,
            'cliente_id' => Cliente::inRandomOrder()->first()->id,
            'forma_pagamento' => $this->faker->randomElement(['dinheiro', 'cartao', 'pix']),
            'total' => $this->faker->randomFloat(2, 50, 1000),
            'status' => $this->faker->randomElement(['pendente', 'paga', 'cancelada']),
            'data_venda' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        ];
    }
}
