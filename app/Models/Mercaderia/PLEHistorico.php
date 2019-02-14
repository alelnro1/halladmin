<?php

namespace App\Models\Mercaderia;

use Illuminate\Database\Eloquent\Model;

class PLEHistorico extends Model
{
    protected $table = "ple_historicos";

    protected $fillable = [
        'vigencia_desde', 'vigencia_hasta', 'precio'
    ];

}
