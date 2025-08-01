<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parcela;
use App\Models\Venda;

class ParcelaController extends Controller
{
    public function index($vendaId)
    {
        $venda = Venda::findOrFail($vendaId);
        $parcelas = $venda->parcelas()->orderBy('data_vencimento')->get();

        return response()->json($parcelas);
    }
    
    public function show($id)
    {
        $parcela = Parcela::findOrFail($id);
        return response()->json($parcela);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'venda_id' => 'required|exists:vendas,id',
            'valor' => 'required|numeric|min:0',
            'data_vencimento' => 'required|date|after_or_equal:today',
            'status' => 'nullable|string|in:pendente,paga,cancelada',
        ]);

        $parcela = Parcela::create($validated);

        return response()->json(['success' => true, 'parcela' => $parcela], 201);
    }

    public function update(Request $request, $id)
    {
        $parcela = Parcela::findOrFail($id);

        $validated = $request->validate([
            'valor' => 'sometimes|required|numeric|min:0',
            'data_vencimento' => 'sometimes|required|date|after_or_equal:today',
            'status' => 'sometimes|required|string|in:pendente,paga,cancelada',
        ]);

        $parcela->update($validated);

        return response()->json(['success' => true, 'parcela' => $parcela]);
    }

    public function destroy($id)
    {
        $parcela = Parcela::findOrFail($id);
        $parcela->delete();

        return response()->json(['success' => true]);
    }
}
