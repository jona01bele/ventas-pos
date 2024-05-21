<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    use HasFactory;
    
    protected $fillable =[
        'precio',
        'cantidad',
        'ventas_id',
        'productos_id',
    ];
}
