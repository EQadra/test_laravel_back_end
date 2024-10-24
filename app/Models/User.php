<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject // Implementar JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Implementar los métodos requeridos por JWTSubject

    public function getJWTIdentifier()
    {
        return $this->getKey(); // Devuelve el identificador único del usuario (por defecto es el id)
    }

    public function getJWTCustomClaims()
    {
        return []; // Puedes agregar aquí cualquier reclamo adicional que quieras incluir en el token
    }
}
