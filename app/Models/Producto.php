<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;  // se utiliza en laravel 11 para archivos como imagnes

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

    // colocar imagne prederminada en laravel 11
    protected function imagen(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value && file_exists(public_path('storage/productos/' . $value))) {
                    return asset('storage/productos/' . $value);
                } else {
                    return asset('storage/productos/noimagen.PNG');
                }
            }
        );
    }

    public function getImagenPath()
    {
        return $this->attributes['imagen'];
    }
}
