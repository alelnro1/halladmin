<?php

namespace App\Models\Precios;

use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    protected $table = "price_list";

    protected $fillable = [
        'nombre', 'descripcion', 'vigencia_desde', 'vigencia_hasta', 'dias', 'es_default', 'activo',
        'local_id', 'negocio_id'
    ];

    public function PriceListEntries()
    {
        return $this->hasMany(PriceListEntry::class);
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getVigenciaDesde()
    {
        return $this->vigencia_desde;
    }

    public function getVigenciaHasta()
    {
        return $this->vigencia_hasta;
    }
}
