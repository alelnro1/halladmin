<?php

namespace App\Models;

use App\Models\Mercaderia\Articulo;
use App\Models\Mercaderia\DatosArticulo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Negocio extends Model
{
    use SoftDeletes;

    protected $table = "negocios";

    protected $fillable = ['nombre', 'cuit', 'condicion_iva'];

    protected $dates = ['deleted_at'];

    public function getNombre()
    {
        return $this->nombre;
    }

    public function Locales()
    {
        return $this->hasMany(Local::class);
    }

    public function Clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function Proveedores()
    {
        return $this->hasMany(Proveedor::class);
    }

    public function DatosArticulos()
    {
        return $this->hasMany(DatosArticulo::class);
    }

    public function Articulos()
    {
        return $this->hasManyThrough(Articulo::class, DatosArticulo::class);
    }

    public function esResponsableInscripto()
    {
        return $this->condicion_iva == "responsable_inscripto";
    }

    public function esMonotributista()
    {
        return $this->condicion_iva == "monotributista";
    }

    /**
     * Filtramos los comprobantes que puede emitir un negocio en base a su condicion frente al IVA
     *
     * @param $tipos_comprobantes
     * @return mixed
     */
    public function filtrarComprobantesPorCondicionIVA($tipos_comprobantes)
    {
        if ($this->esResponsableInscripto()) {
            // Definimos que comprobantes vamos a aceptar si el negocio es responsable inscripto
            $comprobantes_validos = [
                'Factura A',
                'Nota de Débito A',
                'Nota de Crédito A',
                'Factura B',
                'Nota de Débito B',
                'Nota de Crédito B',
            ];
        } else {
            // Definimos que comprobantes vamos a aceptar si el negocio es responsable inscripto
            $comprobantes_validos = [
                'Factura C',
                'Nota de Débito C',
                'Nota de Crédito C'
            ];
        }

        $tipos_comprobantes =
            $tipos_comprobantes->filter(function ($item) use ($comprobantes_validos) {
                return in_array($item->Desc, $comprobantes_validos);
            });

        return $tipos_comprobantes;
    }

    /**
     * Getter del negocio
     *
     * @param $negocio_id
     * @return mixed
     */
    public static function getNegocioPorId($negocio_id)
    {
        return self::where('id', $negocio_id)->first();
    }

    public function getProveedores()
    {
        return $this->Proveedores()->get();
    }

    /**
     * Obtenemos todos los clientes del local
     *
     * @return mixed
     */
    public function getClientes()
    {
        return $this->Clientes()->get();
    }

    public function getArticulos()
    {
        $articulos = $this->Articulos()->get();

        return $articulos;
    }

    public function getDefaultPriceList()
    {
        
    }
}
