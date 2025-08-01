<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_venda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venda_id')->constrained('venda')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produto')->onDelete('cascade');
            $table->integer('quantidade');
            $table->decimal('preco_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->string('status')->default('ativo');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('item_venda');
    }
};
