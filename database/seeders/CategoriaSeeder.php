<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categoria::create([
            'nombre' => 'Cursos',
            'imagen'=> 'https://dummyimage.com/200x150/5c5756/fff',
        ]);
        Categoria::create([
            'nombre' => 'Tenis',
            'imagen'=> 'https://dummyimage.com/200x150/5c5756/fff',
        ]);
        Categoria::create([
            'nombre' => 'Celulares',
            'imagen'=> 'https://dummyimage.com/200x150/5c5756/fff',
        ]);
        Categoria::create([
            'nombre' => 'Computasoras',
            'imagen'=> 'https://dummyimage.com/200x150/5c5756/fff',
        ]);

    }
}
