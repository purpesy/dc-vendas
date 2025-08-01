<?php

use App\Http\Controllers\VendedorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\VendaController;
use App\Http\Controllers\ItemVendaController;
use App\Http\Controllers\ParcelaController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login.form'));

Route::get('/login', [VendedorController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [VendedorController::class, 'login'])->name('login.submit');
Route::get('/register', [VendedorController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [VendedorController::class, 'register'])->name('register.submit');
Route::post('/logout', [VendedorController::class, 'logout'])->name('logout');

Route::get('/dash', [DashboardController::class, 'index'])->name('dash');

Route::resource('clientes', ClienteController::class)->except(['show']);
Route::resource('produtos', ProdutoController::class)->except(['show']);
Route::resource('vendas', VendaController::class);

Route::get('vendas/{venda}/itens', [ItemVendaController::class, 'listByVenda'])->name('itens.index');
Route::post('itens', [ItemVendaController::class, 'store'])->name('itens.store');
Route::put('itens/{id}', [ItemVendaController::class, 'update'])->name('itens.update');
Route::delete('itens/{id}', [ItemVendaController::class, 'destroy'])->name('itens.destroy');

Route::get('vendas/{venda}/parcelas', [ParcelaController::class, 'index'])->name('parcelas.index');
Route::get('parcelas/{id}', [ParcelaController::class, 'show'])->name('parcelas.show');
Route::post('parcelas', [ParcelaController::class, 'store'])->name('parcelas.store');
Route::put('parcelas/{id}', [ParcelaController::class, 'update'])->name('parcelas.update');
Route::delete('parcelas/{id}', [ParcelaController::class, 'destroy'])->name('parcelas.destroy');
