<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendedor_id')->constrained('vendedor')->onDelete('cascade');
            $table->foreignId('cliente_id')->constrained('cliente')->onDelete('cascade');
            $table->string('forma_pagamento');
            $table->decimal('total', 10, 2);
            $table->date('data_venda');
            $table->string('status')->default('pendente');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('venda');
    }
};
