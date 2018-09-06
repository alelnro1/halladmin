<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    protected $table = 'ofertas';

    protected $fillable = [
        'nombre', 'descripcion', 'estado_id', 'articulo_id', 'fecha_inicio', 'fecha_fin',
    ];

    public function Archivos() {
        return $this->hasMany(ArchivoOferta::class);
    }

    public function Locales() {
        return $this->belongsToMany(Local::class, 'local_oferta', 'local_id', 'oferta_id')
            ->withPivot('activa');
    }
    
    public function Articulos() {
        return $this->belongsToMany(Articulo::class, 'articulo_oferta', 'local_id', 'oferta_id')
            ->withPivot('activa');
    }
}
