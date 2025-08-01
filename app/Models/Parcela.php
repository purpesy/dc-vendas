<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parcela extends Model
{
    use HasFactory;
    protected $table = 'parcela';

    protected $fillable = [
        'venda_id',
        'numero',
        'valor',
        'data_vencimento',
        'status'
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }
}
