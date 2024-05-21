<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    protected $fillable = [
        'total',
        'item',
        'cantidadpagada',
        'cambio',
        'estado',
        'id_usuario',
    ];   
}
