<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProdutoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => $this->faker->words(2, true),
            'preco' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
