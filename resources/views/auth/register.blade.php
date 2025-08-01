<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrar - Sistema</title>
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-semibold mb-6 text-center">Crie sua conta</h2>

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            <div class="mb-4">
                <label for="nome" class="block text-gray-700 mb-1">Nome Completo</label>
                <input
                    id="nome"
                    name="nome"
                    type="text"
                    value="{{ old('nome') }}"
                    required
                    class="form-control @error('nome') is-invalid @enderror"
                    placeholder="Seu nome"
                />
                @error('nome')
                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 mb-1">E-mail</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="seu@email.com"
                />
                @error('email')
                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 mb-1">Senha</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Sua senha"
                />
                @error('password')
                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 mb-1">Confirme a Senha</label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    required
                    class="form-control"
                    placeholder="Confirme sua senha"
                />
            </div>

            <button type="submit" class="btn btn-success w-full">
                Registrar
            </button>
        </form>

        <p class="mt-6 text-center text-gray-600">
            Já tem uma conta?
            <a href="{{ route('login.form') }}" class="text-green-600 hover:underline font-semibold">Entre aqui</a>
        </p>
    </div>

</body>
</html>
