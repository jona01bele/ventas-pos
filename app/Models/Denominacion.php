<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Denominacion extends Model
{
    use HasFactory;
    protected $fillable = [
        'tipomoneda',
        'valor',
        'imagen',
    ];

    protected function imagen(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value && file_exists(public_path('storage/monedas/' . $value))) {
                    return asset('storage/monedas/' . $value);
                } else {
                    return asset('storage/monedas/noimagen.png');
                }
            }
        );
    }

    public function getImagenPath()
    {
        return $this->attributes['imagen'];
    }

}
