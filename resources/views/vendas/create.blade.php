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

    <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Nova Venda</h2>

    <form action="{{ route('vendas.store') }}" method="POST" id="venda-form">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Cliente</label>
                <select name="cliente_id" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-400" required>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                    @endforeach
                </select>
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
                <option value="pix">Pix</option>
                <option value="cartao">Cartão</option>
                <option value="dinheiro">Dinheiro</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Número de Parcelas</label>
            <select id="parcelas-select" class="w-24 px-3 py-2 border rounded">
                <option value="1">1</option>
                @for ($i = 2; $i <= 12; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
            <div id="parcelas-info" class="mt-2 font-semibold text-sm text-gray-700"></div>
        </div>

        <input type="hidden" name="produtos_json" id="produtos-json" />
        <input type="hidden" name="parcelas" id="parcelas-hidden" />

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded transition">
                Salvar Venda
            </button>
        </div>
    </form>
</div>

<script>
    const produtosSelecionados = [];

    const produtoSelect = document.getElementById('produto-select');
    const quantidadeInput = document.getElementById('quantidade-input');
    const precoInput = document.getElementById('preco-input');
    const addProdutoBtn = document.getElementById('add-produto-btn');
    const produtosTbody = document.getElementById('produtos-tbody');
    const totalInput = document.getElementById('total-input');
    const parcelasSelect = document.getElementById('parcelas-select');
    const parcelasInfo = document.getElementById('parcelas-info');
    const produtosJsonInput = document.getElementById('produtos-json');
    const parcelasHidden = document.getElementById('parcelas-hidden');

    produtoSelect.addEventListener('change', () => {
        const selectedOption = produtoSelect.selectedOptions[0];
        precoInput.value = selectedOption?.dataset?.preco || '';
    });

    addProdutoBtn.addEventListener('click', () => {
        const produtoId = produtoSelect.value;
        const quantidade = parseInt(quantidadeInput.value);
        const precoUnitario = parseFloat(precoInput.value);

        if (!produtoId || quantidade < 1 || isNaN(precoUnitario)) return;

        const nomeProduto = produtoSelect.selectedOptions[0].text;
        const existente = produtosSelecionados.find(p => p.produto_id === produtoId);

        if (existente) {
            existente.quantidade += quantidade;
        } else {
            produtosSelecionados.push({ produto_id: produtoId, nome: nomeProduto, quantidade, preco_unitario: precoUnitario });
        }

        atualizarTabelaProdutos();
        limparCamposProduto();
        atualizarParcelas();
    });

    function removerProduto(id) {
        const index = produtosSelecionados.findIndex(p => p.produto_id === id.toString());
        if (index !== -1) produtosSelecionados.splice(index, 1);
        atualizarTabelaProdutos();
        atualizarParcelas();
    }

    function atualizarTabelaProdutos() {
        produtosTbody.innerHTML = '';
        let total = 0;
        produtosSelecionados.forEach(p => {
            const subtotal = p.quantidade * p.preco_unitario;
            total += subtotal;
            produtosTbody.innerHTML += `
                <tr>
                    <td class="border p-2">${p.nome}</td>
                    <td class="border p-2">${p.quantidade}</td>
                    <td class="border p-2">R$ ${p.preco_unitario.toFixed(2).replace('.', ',')}</td>
                    <td class="border p-2">R$ ${subtotal.toFixed(2).replace('.', ',')}</td>
                    <td class="border p-2">
                        <button type="button" class="px-2 py-1 bg-red-500 text-white rounded" onclick="removerProduto(${p.produto_id})">Remover</button>
                    </td>
                </tr>`;
        });
        totalInput.value = `R$ ${total.toFixed(2).replace('.', ',')}`;
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
            parcelasHidden.value = '';
            return;
        }
        let html = '<ul class="list-disc list-inside">';
        for (let i = 1; i <= n; i++) {
            const valor = calcularValorParcela(total, i);
            html += `<li>${i}x de R$ ${valor.toFixed(2).replace('.', ',')}</li>`;
        }
        html += '</ul>';
        parcelasInfo.innerHTML = html;
        parcelasHidden.value = n;
    }

    parcelasSelect.addEventListener('change', atualizarParcelas);
</script>
