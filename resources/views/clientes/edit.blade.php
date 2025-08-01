<x-head />
<div class="container mx-auto p-4">
    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('dash') }}" class="btn btn-secondary">← Voltar para Dashboard</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>

    <h2 class="text-2xl font-bold mb-4">Editar Cliente</h2>

    <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" value="{{ old('nome', $cliente->nome) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $cliente->email) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar Cliente</button>
    </form>
</div>
