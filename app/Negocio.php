<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Negocio extends Model
{
    use SoftDeletes;

    protected $table = "negocios";

    protected $fillable = [ 'nombre' ];

    protected $dates = ['deleted_at'];

    public function Locales()
    {
        return $this->hasMany(Local::class);
    }

    public function Clientes()
    {
        return $this->hasMany(Cliente::class);
    }
}
