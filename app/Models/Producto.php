<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'codigobarras',
        'categoria_id',
        'costo',
        'precio',
        'stock',
        'alertas',
        'imagen',
    ];

    public function categoria(){
        return $this->belongsTo(Categoria::class , 'categoria_id');
    }
}
