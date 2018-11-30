<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedioPago extends Model
{
    protected $table = "medios_pago";

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getImagen()
    {
        return $this->imagen;
    }
}
