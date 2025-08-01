# DC Vendas

Sistema de controle de vendas desenvolvido em Laravel.

## Funcionalidades
- Cadastro, edição e exclusão de clientes, produtos, vendedores e vendas
- Edição de vendas com seleção de cliente, vendedor, produtos, status, data, forma de pagamento e parcelas
- Cálculo automático de total e parcelas
- Autenticação de usuário

## Requisitos
- PHP >= 8.0
- Composer
- MySQL ou outro banco compatível

## Instalação
1. Clone o repositório
2. Instale as dependências PHP:
   ```bash
   composer install
3. Configure o arquivo `.env` com as credenciais do banco de dados
4. Rode as migrations:
   ```bash
   php artisan migrate
   ```
5. (Opcional) Popule o banco com seeders:
   ```bash
   php artisan db:seed
   ```
6. Inicie o servidor:
   ```bash
   php artisan serve
   ```

## Estrutura de Pastas
- `app/Models/` — Modelos Eloquent (Venda, ItemVenda, Cliente, Produto, Vendedor)
- `app/Http/Controllers/` — Lógica dos controllers
- `resources/views/` — Views Blade (páginas)
- `routes/` — Rotas da aplicação
- `public/` — Arquivos públicos (index.php, assets)

## Uso Básico
- Acesse `/login` para autenticação
- Navegue pelo dashboard para gerenciar vendas, clientes e produtos
- Edite e crie vendas com todos os campos visíveis e cálculo automático de valores

