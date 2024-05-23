<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Jonathan',
            'email'=> 'jonathanba000@gmail.com',
            'password'=> bcrypt('12345674j'),
            'perfil'=> 'ADMINISTRADOR',
            'telefono'=> '305321654',
            'estado'=> 'ACTIVO',
        ]);

        User::create([
            'name' => 'Ruth',
            'email'=> 'ruth@gmail.com',
            'password'=> bcrypt('12345674j'),
            'perfil'=> 'EMPLEADO',
            'telefono'=> '3206512495',
            'estado'=> 'ACTIVO',
        ]);

    }
}
