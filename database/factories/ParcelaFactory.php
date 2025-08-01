<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Parcela;

class ParcelaFactory extends Factory
{
    protected $model = Parcela::class;

    public function definition()
    {
        return [
            'venda_id' => $this->faker->numberBetween(1, 10),
            'quantidade_parcelas' => $this->faker->numberBetween(1, 12),
            'data_vencimento' => $this->faker->dateTimeBetween('now', '+1 year'),
            'valor' => $this->faker->randomFloat(2, 20, 500),
            'status' => $this->faker->randomElement(['pendente', 'paga']),
        ];
    }
}
