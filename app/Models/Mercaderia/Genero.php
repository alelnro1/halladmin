<?php

namespace App\Models\Mercaderia;

use Illuminate\Database\Eloquent\Model;

class Genero extends Model
{
    protected $table = 'generos';

    protected $fillable = [
        'nombre'
    ];

    public function Talles()
    {
        return $this->hasMany(Talle::class);
    }

    public function DatosArticulo()
    {
        return $this->hasMany(DatosArticulo::class);
    }

    public function getNombre()
    {
        return $this->nombre;
    }
}
