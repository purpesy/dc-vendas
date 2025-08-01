<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;

class ProdutoController extends Controller
{   
    public function index()
    {
        $produtos = Produto::all();
        if ($produtos->isEmpty()) {
            return response()->json(['message' => 'Nenhum produto encontrado'], 404);
        }
        return view('produtos.index', compact('produtos'));
        return redirect()->route('dash');
    }

    public function listar()
    {
        $produtos = Produto::all();
        if ($produtos->isEmpty()) {
            return response()->json(['message' => 'Nenhum produto encontrado'], 404);
        }
        return response()->json($produtos);
    }

    public function create()
    {
        $produtos = Produto::all();
        if ($produtos->isEmpty()) {
            return response()->json(['message' => 'Nenhum produto encontrado'], 404);
        }
        return view('produtos.create', compact('produtos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
        ]);

        Produto::create($validated);
        return redirect()->route('dash')->with('success', 'Produto cadastrado com sucesso!');
    }

    public function edit($id)
    {
        $produto = Produto::findOrFail($id);
        if (!$produto) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }
        return view('produtos.edit', compact('produto'));
    }

    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'preco' => 'sometimes|required|numeric|min:0',
        ]);

        $produto->update($validated);
        return redirect()->route('dash')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);
        $produto->delete();
        return redirect()->route('dash')->with('success', 'Produto deletado com sucesso.');
    }


}
