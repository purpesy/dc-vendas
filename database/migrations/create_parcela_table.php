<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parcela', function (Blueprint $table) {
            $table->id();
            $table->integer('numero');
            $table->foreignId('venda_id')->constrained('venda')->onDelete('cascade');
            $table->decimal('valor', 10, 2);
            $table->date('data_vencimento');
            $table->string('status')->default('pendente');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('parcela');
    }
};
