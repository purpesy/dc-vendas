<x-head />
<div class="container mx-auto p-4">
    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('dash') }}" class="btn btn-secondary">← Voltar para Dashboard</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>

    <h2 class="text-2xl font-bold mb-4">Novo Produto</h2>

    <form action="{{ route('produtos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nome do Produto</label>
            <input type="text" name="nome" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Preço</label>
            <input type="number" step="0.01" name="preco" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-success">Cadastrar Produto</button>
    </form>
</div>
