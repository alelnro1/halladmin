<?php

namespace App\Models\Mercaderia;

use Illuminate\Database\Eloquent\Model;

class Talle extends Model
{
    protected $table = 'talles';

    protected $fillable = [
        'nombre', 'categoria_id'
    ];

    public function Genero() {
        return $this->belongsTo(Genero::class, 'genero_id');
    }

    public function Articulos() {
        return $this->hasMany(Articulo::class);
    }
}
