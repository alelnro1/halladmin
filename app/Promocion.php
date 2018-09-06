<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class promocion extends Model
{
    protected $table = 'promociones';

    protected $fillable = [
        'nombre', 'descripcion', 'archivo', 'estado', 'fecha', 'password', 'domicilio', 'email', 'telefono'
    ];
}
