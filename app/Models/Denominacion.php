<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denominacion extends Model
{
    use HasFactory;
    protected $fillable = [
        'tipomoneda',
        'valor',
        'imagen',
    ];

}
