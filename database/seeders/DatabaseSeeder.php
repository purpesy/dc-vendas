<?php

namespace Database\Seeders;

use App\Models\Vendedor;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\Venda;
use App\Models\ItemVenda;
use App\Models\Parcela;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Cliente::factory(10)->create();
        Vendedor::factory(5)->create();
        Produto::factory(20)->create();

        Venda::factory(10)->create()->each(function ($venda) {
            $total = 0;

            for ($i = 0; $i < rand(1, 4); $i++) {
                $produto = Produto::inRandomOrder()->first();
                $quantidade = rand(1, 3);
                $preco = $produto->preco;
                $subtotal = $quantidade * $preco;

                $venda->itens()->create([
                    'produto_id' => $produto->id,
                    'quantidade' => $quantidade,
                    'preco_unitario' => $preco,
                    'subtotal' => $subtotal,
                    'status' => 'ativo',
                ]);

                $total += $subtotal;
            }

            $venda->total = $total;
            $venda->save();

            $numParcelas = rand(1, 6);
            $jurosPercentual = 0.02;

            $totalComJuros = $total;
            if ($numParcelas > 1) {
                $totalComJuros = $total * (1 + $jurosPercentual * $numParcelas);
            }

            $valorParcela = $totalComJuros / $numParcelas;

            for ($i = 1; $i <= $numParcelas; $i++) {
                $data = now()->addMonths($i);
                $venda->parcelas()->create([
                    'numero' => $i,
                    'valor' => round($valorParcela, 2),
                    'data_vencimento' => $data,
                    'status' => 'pendente',
                ]);
            }
            $venda->total = round($totalComJuros, 2);
            $venda->save();
        });
    }
}
