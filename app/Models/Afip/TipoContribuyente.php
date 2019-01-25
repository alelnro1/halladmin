<?php

namespace App\Models\Afip;

use Illuminate\Database\Eloquent\Model;

class TipoContribuyente extends Model
{
    protected $table = "afip_tipos_contribuyentes";

    protected $fillable = [
        'nombre'
    ];
}
