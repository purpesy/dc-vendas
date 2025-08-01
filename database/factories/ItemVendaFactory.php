<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ItemVenda;
use App\Models\Venda;
use App\Models\Produto;

class ItemVendaFactory extends Factory
{
    protected $model = ItemVenda::class;

    public function definition()
    {
        $precoUnitario = $this->faker->randomFloat(2, 10, 500);
        $quantidade = $this->faker->numberBetween(1, 10);
        return [
            'venda_id' => Venda::inRandomOrder()->first()->id,
            'produto_id' => Produto::inRandomOrder()->first()->id,
            'quantidade' => $quantidade,
            'preco_unitario' => $precoUnitario,
            'subtotal' => $precoUnitario * $quantidade, // calculado
            'status' => $this->faker->randomElement(['ativo', 'cancelado']),
        ];
    }
}
