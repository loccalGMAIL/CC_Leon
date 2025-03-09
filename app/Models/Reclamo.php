<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclamo extends Model
{
    protected $table = 'reclamos_rto';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'rto_ir','descripcionReclamoRto','estadoReclamoRto','resolucionReclamoRto', 'created_at', 'updated_at'];
    protected $hidden = ['created_at', 'updated_at'];
    public $timestamps = true;
}
