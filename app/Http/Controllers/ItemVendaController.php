<?php

namespace App\Http\Controllers;

use App\Models\ItemVenda;
use Illuminate\Http\Request;

class ItemVendaController extends Controller
{
    public function listByVenda($vendaId)
    {
        $itens = ItemVenda::with('produto')->where('venda_id', $vendaId)->get();
        return response()->json($itens);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'venda_id' => 'required|exists:venda,id',
            'produto_id' => 'required|exists:produto,id',
            'quantidade' => 'required|integer|min:1',
            'preco_unitario' => 'required|numeric|min:0',
            'status' => 'required|string|max:20',
        ]);

        $validated['subtotal'] = $validated['quantidade'] * $validated['preco_unitario'];

        $item = ItemVenda::create($validated);

        return response()->json(['success' => true, 'item' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = ItemVenda::findOrFail($id);

        $validated = $request->validate([
            'produto_id' => 'required|exists:produto,id',
            'quantidade' => 'required|integer|min:1',
            'preco_unitario' => 'required|numeric|min:0',
            'status' => 'required|string|max:20',
        ]);

        $validated['subtotal'] = $validated['quantidade'] * $validated['preco_unitario'];

        $item->update($validated);

        return response()->json(['success' => true, 'item' => $item]);
    }

    public function destroy($id)
    {
        $item = ItemVenda::findOrFail($id);
        $item->delete();

        return response()->json(['success' => true]);
    }
}
