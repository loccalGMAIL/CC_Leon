<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = "cotizacion";
    protected $primaryKey = "id";
    protected $fillable = ['precioDolar'];
    public $timestamps = true;
    protected $casts = [
        'precioDolar' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
}
