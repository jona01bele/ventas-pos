<?php

namespace Database\Seeders;
use App\Models\Denominacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DenominacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Denominacion::create([
            'tipomoneda' =>'BILLETE',
            'valor' => 2000 ,
            
        ]);
        Denominacion::create([
            'tipomoneda' =>'BILLETE',
            'valor' => 5000 ,
            
        ]);
        Denominacion::create([
            'tipomoneda' =>'BILLETE',
            'valor' => 10000 ,
            
        ]);
        Denominacion::create([
            'tipomoneda' =>'BILLETE',
            'valor' => 20000 ,
            
        ]);
        Denominacion::create([
            'tipomoneda' =>'MONEDA',
            'valor' => 1000 ,
            
        ]);
        Denominacion::create([
            'tipomoneda' =>'MONEDA',
            'valor' => 500 ,
            
        ]);
        Denominacion::create([
            'tipomoneda' =>'MONEDA',
            'valor' => 200 ,
            
        ]);
        Denominacion::create([
            'tipomoneda' =>'MONEDA',
            'valor' => 100 ,
            
        ]);
        Denominacion::create([
            'tipomoneda' =>'OTRO',
            'valor' => 0 ,
            
        ]);
       
    }
}
