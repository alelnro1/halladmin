<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = [
        'nombre', 'padre_id'
    ];

    /* Mutators */
    public function setPadreIdAttribute($value)
    {
        $this->attributes['padre_id'] = $value ?: null;
    }

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = ucfirst($value) ?: null;
    }

    public function Talles()
    {
        return $this->hasMany(Talle::class);
    }

    public function Articulos()
    {
        return $this->hasMany(Articulo::class);
    }

    public function CategoriaPadre()
    {
        return $this->hasOne(Categoria::class, 'id', 'padre_id');
    }

    public function CategoriasHijas()
    {
        return $this->hasMany(Categoria::class, 'padre_id', 'id');
    }

    public function Locales()
    {
        return $this->belongsToMany(Local::class, 'categoria_local', 'categoria_id', 'local_id');
    }

    public function Genero()
    {
        return $this->belongsTo(Genero::class);
    }
}
