<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; 
use Illuminate\Database\Eloquent\Casts\Attribute; // util para la imagen cuando se va colocar una predifinida en caso que no se guarde un avatar

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'perfil',
        'telefono',
        'estado',
        'imagen',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function imagen(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value && file_exists(public_path('storage/usuarios/' . $value))) {
                    return asset('storage/usuarios/' . $value);
                } else {
                    return asset('storage/usuarios/noimagen.png');
                }
            }
        );
    }

    public function getImagenPath()
    {
        return $this->attributes['imagen'];
    }


}
