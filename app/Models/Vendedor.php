<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Vendedor extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'vendedor';

    protected $fillable = ['nome', 'email', 'password'];

    public function vendas()
    {
        return $this->hasMany(Venda::class);
    }
}
