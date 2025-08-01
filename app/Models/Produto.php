<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produto extends Model
{
    use HasFactory;
    protected $table = 'produto';

    protected $fillable = ['nome', 'preco'];
    
    public function itensVenda()
    {
        return $this->hasMany(ItemVenda::class);
    }
}
