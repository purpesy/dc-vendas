<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venda extends Model
{
    use HasFactory;
    protected $table = 'venda';

    protected $fillable = [
        'vendedor_id',
        'cliente_id',
        'forma_pagamento',
        'total',
        'data_venda',
        'status'
    ];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class, 'vendedor_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
    public function itens()
    {
        return $this->hasMany(ItemVenda::class);
    }
    public function parcelas()
    {
        return $this->hasMany(Parcela::class);
    }
}
