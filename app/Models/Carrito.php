<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    public $fillable = [
        'producto_id',
        'nombre',
        'precio',
        'cantidad',
        'imagen',
    ];

}
