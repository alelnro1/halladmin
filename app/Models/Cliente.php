<?php

namespace App\Models;

use App\Models\Ventas\Venta;
use App\Models\Ventas\VentaCancelada;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'nombre', 'apellido', 'domicilio', 'email', 'telefono', 'saldo', 'negocio_id'
    ];
    
    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function getNombreCompleto()
    {
        return $this->getNombre() . " " . $this->getApellido();
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function getDomicilio()
    {
        return $this->domicilio;
    }

    /*public function Locales()
    {
        return $this->belongsToMany(Local::class, 'cliente_local', 'cliente_id', 'local_id');
    }*/
    
    public function Negocio()
    {
        return $this->belongsTo(Negocio::class);
    }

    public function Compras()
    {
        return $this->hasMany(Venta::class);
    }

    public function VentaCancelada()
    {
        return $this->hasMany(VentaCancelada::class);
    }

    /**
     * Actualiza el saldo de un cliente luego de una venta
     * @param $total_venta
     */
    public function actualizaTuSaldo($total_venta)
    {
        if ($total_venta <= 0) {
            // Por esta venta, hay saldo a favor => se lo sumo al cliente
            $this->saldo = $this->saldo + abs($total_venta);
        } else {
            $nuevo_saldo = $this->saldo - $total_venta;

            if ($nuevo_saldo > 0) {
                $this->saldo = $nuevo_saldo;
            } else {
                // El saldo no puede ser negativo
                $this->saldo = 0;
            }
        }

        $this->save();
    }

    public function getCompras()
    {
        return $this->Compras()->get();
    }
}
