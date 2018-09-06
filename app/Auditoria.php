<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = "auditoria";

    protected $fillable = [
        'costo_anterior', 'costo_nuevo', 'precio_anterior', 'precio_nuevo', 'stock_anterior', 'stock_nuevo',
        'cambio_id', 'venta_id', 'articulo_id', 'proveedor_id', 'user_id', 'oferta_id'
    ];

    /* Mutators */
    public function setCambioIdAttribute($value) {
        $this->attributes['cambio_id'] = $value ?: null;
    }

    public function setVentaIdAttribute($value) {
        $this->attributes['venta_id'] = $value ?: null;
    }

    public function setProveedorIdAttribute($value) {
        $this->attributes['proveedor_id'] = $value ?: null;
    }

    public function setOfertaIdAttribute($value) {
        $this->attributes['oferta_id'] = $value ?: null;
    }

    public function scopeIngresoNuevoArticulo()
    {
        return "Ingreso de artículo nuevo";
    }

    public function scopeIngresoArticuloExistente()
    {
        return "Ingreso de articulo existente";
    }

}
