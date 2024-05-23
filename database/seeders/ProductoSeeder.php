<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Producto::create([
            'nombre' =>  'LARAVEL y LIVEWIRE',
            'codigobarras' =>  '987654321',
            'categoria_id' => 1,
            'costo' =>  20000,
            'precio' =>  30000,
            'stock' =>  150,
            'alertas' =>  10,
            'imagen' =>  'curso.png',
        ]);
        Producto::create([
            'nombre' =>  'TENIS PUMA',
            'codigobarras' =>  '987654322',
            'categoria_id' =>  2,
            'costo' =>  50000,
            'precio' =>  80000,
            'stock' =>  100,
            'alertas' =>  10,
            'imagen' =>  'tenis.png',
        ]);
        Producto::create([
            'nombre' =>  'IPHONE 11',
            'codigobarras' =>  '987654323',
            'categoria_id' =>  3,
            'costo' =>  180000,
            'precio' =>  200000,
            'stock' =>  50,
            'alertas' =>  5,
            'imagen' =>  'iphone.png',
        ]);

        Producto::create([
            'nombre' =>  'LENOVO',
            'codigobarras' =>  '987654324',
            'categoria_id' =>  4,
            'costo' =>  250000,
            'precio' =>  300000,
            'stock' =>  80,
            'alertas' =>  10,
            'imagen' =>  'lenovo.png',
        ]);
        

    }
}
