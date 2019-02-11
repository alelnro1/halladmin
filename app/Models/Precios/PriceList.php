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
}
