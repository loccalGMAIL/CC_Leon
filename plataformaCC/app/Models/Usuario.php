<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasRoles, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'idUsuario';
    
    protected $fillable = [
        'nombreUsuario',
        'apellidoUsuario',
        'dniUsuario',
        'mailUsuario',
        'usser',
        'pass',
        'perfilUsuario'
    ];

    protected $hidden = [
        'pass',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->pass;
    }
}