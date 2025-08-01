<x-head />
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md mt-8">
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('dash') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded transition">
            ← Voltar para Dashboard
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded transition">
                Logout
            </button>
        </form>
    </div>

    <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Editar Venda #{{ $venda->id }}</h2>

    <form action="{{ route('vendas.update', $venda->id) }}" method="POST" id="venda-form">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Cliente</label>
                <select name="cliente_id" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-400" required>
                    @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ $cliente->id == $venda->cliente_id ? 'selected' : '' }}>
                        {{ $cliente->nome }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Vendedor</label>
                <select name="vendedor_id" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-400" required>
                    @foreach($vendedores as $vendedor)
                    <option value="{{ $vendedor->id }}" {{ $vendedor->id == $venda->vendedor_id ? 'selected' : '' }}>
                        {{ $vendedor->nome }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-400" required>
                    <option value="paga" {{ $venda->status == 'paga' ? 'selected' : '' }}>Paga</option>
                    <option value="pendente" {{ $venda->status == 'pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="cancelada" {{ $venda->status == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Data da Venda</label>
                <input type="date" name="data_venda" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-400" value="{{ old('data_venda', isset($venda->data_venda) ? \Carbon\Carbon::parse($venda->data_venda)->format('Y-m-d') : '') }}" required />
            </div>
        </div>

        <div class="mb-6">
            <h3 class="font-semibold mb-2">Adicionar Produto</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block mb-1">Produto</label>
                    <select id="produto-select" class="w-full px-3 py-2 border rounded">
                        <option value="">Selecione</option>
                        @foreach($produtos as $produto)
                        <option value="{{ $produto->id }}" data-preco="{{ $produto->preco }}">
                            {{ $produto->nome }} (R$ {{ number_format($produto->preco, 2, ',', '.') }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-1">Quantidade</label>
                    <input type="number" id="quantidade-input" class="w-full px-3 py-2 border rounded" min="1" value="1" />
                </div>
                <div>
                    <label class="block mb-1">Preço Unitário (R$)</label>
                    <input type="number" id="preco-input" class="w-full px-3 py-2 border rounded bg-gray-100" step="0.01" readonly />
                </div>
                <div>
                    <button type="button" id="add-produto-btn" class="w-full px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Adicionar
                    </button>
                </div>
            </div>

            <table class="table-auto w-full mt-4 text-left border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">Produto</th>
                        <th class="p-2 border">Quantidade</th>
                        <th class="p-2 border">Preço Unitário (R$)</th>
                        <th class="p-2 border">Subtotal (R$)</th>
                        <th class="p-2 border">Ações</th>
                    </tr>
                </thead>
                <tbody id="produtos-tbody" class="text-sm">
                </tbody>
            </table>
        </div>

        <div class="mb-6 text-right">
            <label class="block font-semibold mb-1">Total (R$):</label>
            <input type="text" id="total-input" name="total" class="w-48 px-3 py-2 border rounded text-right bg-gray-100" value="0,00" readonly />
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Forma de Pagamento</label>
            <select name="forma_pagamento" class="w-full px-3 py-2 border rounded" required>
                <option value="">Selecione</option>
                <option value="pix" {{ $venda->forma_pagamento == 'pix' ? 'selected' : '' }}>Pix</option>
                <option value="cartao" {{ $venda->forma_pagamento == 'cartao' ? 'selected' : '' }}>Cartão</option>
                <option value="dinheiro" {{ $venda->forma_pagamento == 'dinheiro' ? 'selected' : '' }}>Dinheiro</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Número de Parcelas</label>
            @php
            $numeroParcelas = is_numeric($venda->parcelas) ? $venda->parcelas : $venda->parcelas->count();
            @endphp

            <select id="parcelas-select" name="parcelas" class="w-24 px-3 py-2 border rounded" required>
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $i == $numeroParcelas ? 'selected' : '' }}>
                    {{ $i }}
                    </option>
                    @endfor
            </select>
            <div id="parcelas-info" class="mt-2 font-semibold text-sm text-gray-700"></div>
        </div>

        <input type="hidden" name="produtos_json" id="produtos-json" />

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded transition">
                Atualizar Venda
            </button>
        </div>
    </form>
</div>

<script>
    let produtosSelecionados = @json($itensJson);


    const produtoSelect = document.getElementById('produto-select');
    const quantidadeInput = document.getElementById('quantidade-input');
    const precoInput = document.getElementById('preco-input');
    const addProdutoBtn = document.getElementById('add-produto-btn');
    const produtosTbody = document.getElementById('produtos-tbody');
    const totalInput = document.getElementById('total-input');
    const parcelasSelect = document.getElementById('parcelas-select');
    const parcelasInfo = document.getElementById('parcelas-info');
    const produtosJsonInput = document.getElementById('produtos-json');

    produtoSelect.addEventListener('change', () => {
        const selectedOption = produtoSelect.selectedOptions[0];
        precoInput.value = selectedOption?.dataset?.preco || '';
    });

    addProdutoBtn.addEventListener('click', () => {
        const produtoId = produtoSelect.value;
        const quantidade = parseInt(quantidadeInput.value);
        const precoUnitario = parseFloat(precoInput.value);

        if (!produtoId || quantidade < 1 || isNaN(precoUnitario)) {
            alert('Selecione um produto e uma quantidade válida.');
            return;
        }

        const nomeProduto = produtoSelect.selectedOptions[0].text;

        const existenteIndex = produtosSelecionados.findIndex(p => p.produto_id == produtoId);

        if (existenteIndex >= 0) {
            produtosSelecionados[existenteIndex].quantidade += quantidade;
        } else {
            produtosSelecionados.push({
                produto_id: produtoId,
                nome: nomeProduto,
                quantidade: quantidade,
                preco_unitario: precoUnitario
            });
        }

        atualizarTabelaProdutos();
        limparCamposProduto();
        atualizarParcelas();
    });

    function removerProduto(index) {
        produtosSelecionados.splice(index, 1);
        atualizarTabelaProdutos();
        atualizarParcelas();
    }

    function atualizarTabelaProdutos() {
        produtosTbody.innerHTML = '';
        let total = 0;

        produtosSelecionados.forEach((p, index) => {
            const subtotal = p.quantidade * p.preco_unitario;
            total += subtotal;

            produtosTbody.innerHTML += `
                <tr>
                    <td class="border p-2">${p.nome}</td>
                    <td class="border p-2">${p.quantidade}</td>
                    <td class="border p-2">R$ ${p.preco_unitario.toFixed(2).replace('.', ',')}</td>
                    <td class="border p-2">R$ ${subtotal.toFixed(2).replace('.', ',')}</td>
                    <td class="border p-2">
                        <button type="button" class="px-2 py-1 bg-red-500 text-white rounded" onclick="removerProduto(${index})">Remover</button>
                    </td>
                </tr>`;
        });

        // Ajuste: campo total deve ser numérico para o backend
        totalInput.value = total.toFixed(2);
        produtosJsonInput.value = JSON.stringify(produtosSelecionados);
    }

    function limparCamposProduto() {
        produtoSelect.value = '';
        quantidadeInput.value = 1;
        precoInput.value = '';
    }

    function calcularValorParcela(total, n) {
        const taxa = 0.02;
        const totalComJuros = total * Math.pow(1 + taxa, n);
        return totalComJuros / n;
    }

    function atualizarParcelas() {
        const n = parseInt(parcelasSelect.value);
        const total = parseFloat(totalInput.value.replace('R$ ', '').replace(',', '.'));
        if (!total || total === 0) {
            parcelasInfo.textContent = 'Adicione produtos para calcular parcelas.';
            return;
        }
        let html = '<ul class="list-disc list-inside">';
        for (let i = 1; i <= n; i++) {
            const valor = calcularValorParcela(total, i);
            html += `<li>${i}x de R$ ${valor.toFixed(2).replace('.', ',')}</li>`;
        }
        html += '</ul>';
        parcelasInfo.innerHTML = html;
    }

    parcelasSelect.addEventListener('change', atualizarParcelas);

    atualizarTabelaProdutos();
    atualizarParcelas();
</script>