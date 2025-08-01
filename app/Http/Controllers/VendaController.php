<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Venda;
use App\Models\ItemVenda;
use App\Models\Parcela;
use App\Models\Cliente;
use App\Models\Vendedor;
use App\Models\Produto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class VendaController extends Controller
{
    public function index()
    {
        $vendas = Venda::with(['cliente', 'vendedor'])->get();

        if ($vendas->isEmpty()) {
            return response()->json(['message' => 'Nenhuma venda encontrada'], 404);
        }

        return view('vendas.index', compact('vendas'));
    }

    public function show($id)
    {
        $venda = Venda::with(['cliente', 'vendedor', 'itens.produto', 'parcelas'])->findOrFail($id);
        return view('vendas.show', compact('venda'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $produtos = Produto::all();
        $vendedores = Vendedor::all();

        return view('vendas.create', compact('clientes', 'produtos', 'vendedores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:cliente,id',
            'total' => 'required|string',
            'forma_pagamento' => 'required|string',
            'produtos_json' => 'required|json',
            'parcelas' => 'nullable|integer',
        ]);

        $total = str_replace(['R$', '.', ','], ['', '', '.'], $validated['total']);

        $venda = Venda::create([
            'cliente_id' => $validated['cliente_id'],
            'vendedor_id' => Auth::user()->id,
            'data_venda' => Carbon::now(),
            'total' => $total,
            'forma_pagamento' => $validated['forma_pagamento'],
            'parcelas' => $validated['parcelas'],
        ]);

        $produtos = json_decode($validated['produtos_json'], true);

        foreach ($produtos as $item) {
            ItemVenda::create([
                'venda_id' => $venda->id,
                'produto_id' => $item['produto_id'],
                'quantidade' => $item['quantidade'],
                'preco_unitario' => $item['preco_unitario'],
                'subtotal' => $item['quantidade'] * $item['preco_unitario'],
            ]);
        }

        return redirect()->route('dash')->with('success', 'Venda registrada com sucesso.');
    }


    public function edit($id)
    {
        $venda = Venda::with('itens.produto')->findOrFail($id);
        $clientes = Cliente::all();
        $vendedores = Vendedor::all();
        $produtos = Produto::all();

        $itensJson = $venda->itens->map(function ($item) {
            return [
                'produto_id' => $item->produto_id,
                'nome' => $item->produto->nome,
                'quantidade' => $item->quantidade,
                'preco_unitario' => (float) $item->preco_unitario,
            ];
        });

        return view('vendas.edit', compact('venda', 'clientes', 'vendedores', 'produtos', 'itensJson'));
    }



    public function update(Request $request, $id)
    {
        $venda = Venda::findOrFail($id);

        $validated = $request->validate([
            'cliente_id' => 'required|exists:cliente,id',
            'vendedor_id' => 'required|exists:vendedor,id',
            'forma_pagamento' => 'required|string|max:50',
            'status' => 'required|string|in:paga,pendente,cancelada',
            'data_venda' => 'required|date',
            'parcelas' => 'required|integer|min:1|max:12',
            'total' => 'required|numeric|min:0',
        ]);
        $venda->update($validated);

        if ($request->has('produtos_json')) {
            $produtos = json_decode($request->input('produtos_json'), true);

            $venda->itens()->delete();

            foreach ($produtos as $produto) {
                $venda->itens()->create([
                    'produto_id' => $produto['produto_id'],
                    'quantidade' => $produto['quantidade'],
                    'preco_unitario' => $produto['preco_unitario'],
                    'subtotal' => $produto['quantidade'] * $produto['preco_unitario'],
                ]);
            }
        }

        return redirect()->route('dash')->with('success', 'Venda atualizada com sucesso.');
    }


    public function destroy($id)
    {
        $venda = Venda::findOrFail($id);
        $venda->delete();

        return redirect()->route('dash')->with('success', 'Venda deletada com sucesso.');
    }
}
