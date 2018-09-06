<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
        'nombre', 'descripcion', 'archivo', 'usuario_id', 'domicilio', 'email', 'telefono'
    ];

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = ucfirst($value) ?: '';
    }

    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = ucfirst($value) ?: '';
    }

    public function Articulos()
    {
        return $this->belongsToMany(Articulo::class, 'articulo_proveedor', 'proveedor_id', 'articulo_id')
            ->withPivot(['costo', 'cantidad'])
            ->withTimestamps();
    }


}
