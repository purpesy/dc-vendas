<div class="mb-3">
    <a href="{{ route('vendas.create') }}" class="btn btn-success">Adicionar Venda</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Vendedor</th>
            <th>Forma de Pagamento</th>
            <th>Total</th>
            <th>Data da Venda</th>
            <th>Status</th>
            <th>Ações</th> <!-- Coluna para os botões -->
        </tr>
    </thead>
    <tbody>
        @foreach($vendas as $venda)
        <tr>
            <td>{{ $venda->id }}</td>
            <td>{{ $venda->cliente->nome ?? 'N/A' }}</td>
            <td>{{ $venda->vendedor->nome ?? 'N/A' }}</td>
            <td>{{ $venda->forma_pagamento }}</td>
            <td>{{ number_format($venda->total, 2, ',', '.') }}</td>
            <td>{{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y') }}</td>
            <td>{{ $venda->status }}</td>
            <td>
                <a href="{{ route('vendas.edit', $venda->id) }}" class="btn btn-primary btn-sm">Editar</a>

                <form action="{{ route('vendas.destroy', $venda->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Tem certeza que deseja remover esta venda?')" class="btn btn-danger btn-sm">Remover</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
