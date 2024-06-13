<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;


    protected $fillable = [
        'nombre',
        'imagen',
    ];
    public function producto()
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }

   //manera de colocar una imagen prederminada en laravel 11

    protected function imagen(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value && file_exists(public_path('storage/categorias/' . $value))) {
                    return asset('storage/categorias/' . $value);
                } else {
                    return asset('storage/categorias/noimagen.jpg');
                }
            }
        );
    }

    public function getImagenPath()
    {
        return $this->attributes['imagen'];
    }

  
}
