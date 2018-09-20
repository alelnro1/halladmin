<?php

namespace App\Models;

use App\Models\Mercaderia\Articulo;
use App\User;
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

    public function Usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function Articulos()
    {
        return $this->belongsToMany(Articulo::class, 'articulo_proveedor', 'proveedor_id', 'articulo_id')
            ->withPivot(['costo', 'cantidad'])
            ->withTimestamps();
    }

    public function Locales()
    {
        return $this->belongsToMany(Local::class, 'local_proveedor', 'proveedor_id', 'local_id');
    }

    public static function getProveedoresDeNegocio($negocio_id)
    {
        $proveedores =
            self::whereHas('Usuario', function ($query) use($negocio_id) {
                $query->where('negocio_id', $negocio_id);
            })->get();

        return $proveedores;
    }

}
