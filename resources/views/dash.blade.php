
<x-head />

<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Menu Topo -->
    <nav class="bg-white shadow-md p-4 flex justify-between items-center">
        <a href="{{ route('dash') }}" class="text-lg font-bold text-green-600">Meu Sistema</a>

        <div>
            <a href="{{ route('logout') }}"
                class="btn btn-danger btn-sm"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Sair
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </nav>

    <!-- Conteúdo -->
    <main class="flex-grow container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-700">Dashboard</h1>

        <!-- Botões -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Clientes</h2>
                <button class="btn btn-success btn-sm" onclick="loadSection('clientes')">Ver lista</button>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Produtos</h2>
                <button class="btn btn-success btn-sm" onclick="loadSection('produtos')">Ver lista</button>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Vendas</h2>
                <button class="btn btn-success btn-sm" onclick="loadSection('vendas')">Ver lista</button>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <!-- Container das listas -->
        <div id="conteudo-dinamico" class="mt-8"></div>
    </main>

    <!-- Script AJAX -->
    <script>
        function loadSection(tipo) {
            let url = '';

            switch (tipo) {
                case 'clientes':
                    url = "{{ route('clientes.index') }}";
                    break;
                case 'produtos':
                    url = "{{ route('produtos.index') }}";
                    break;
                case 'vendas':
                    url = "{{ route('vendas.index') }}";
                    break;
            }

            $.get(url, function(data) {
                $('#conteudo-dinamico').html(data);
            }).fail(function() {
                $('#conteudo-dinamico').html('<div class="alert alert-danger mt-4">Erro ao carregar dados.</div>');
            });
        }
    </script>

</body>

</html>