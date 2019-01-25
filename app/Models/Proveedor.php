<?php

namespace App\Models;

use App\Models\Mercaderia\Articulo;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
        'nombre', 'descripcion', 'archivo', 'usuario_id', 'domicilio', 'email', 'telefono', 'negocio_id'
    ];

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = ucfirst($value) ?: '';
    }

    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = ucfirst($value) ?: '';
    }

    public function setNegocioIdAttribute()
    {
        $this->attributes['negocio_id'] = session('LOCAL_ACTUAL')->negocio_id;
    }

    public function Usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function Negocio()
    {
        return $this->belongsTo(Negocio::class);
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
    /**
     * Verificamos que un proveedor dado pertenezca al negocio actual
     */
    public function perteneceAlNegocio($negocio_id)
    {
        $negocio = Negocio::find($negocio_id);

        return $negocio->Proveedores->contains($this);
    }

    /**
     * Obtenemos los articulos del proveedor
     *
     * @return mixed
     */
    public function getArticulos()
    {
        return
            $this->load([
                'Articulos' => function ($query) {
                    $query->with([
                        'DatosArticulo',
                        'Local' => function ($query) {
                            $query->select(['id', 'nombre']);
                        }
                    ]);
                },
            ])
                ->Articulos;
    }

}
