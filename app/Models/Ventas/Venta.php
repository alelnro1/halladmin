<?php

namespace App\Models\Ventas;

use App\Http\Controllers\Controller;
use App\Models\Cambio;
use App\Models\Cliente;
use App\Models\Local;
use App\Models\Mercaderia\Articulo;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Venta extends Model
{
    protected $table = "ventas";

    protected $fillable = [
        'cliente_id', 'user_id', 'local_id', 'monto_total', 'cantidad_articulos', 'nro_orden', 'medio_de_pago'
    ];

    public static function getVenta($nro_orden)
    {
        $venta = Venta::where('nro_orden', $nro_orden)
            ->with([
                    'Articulos' => function ($query) {
                        $query->with([
                            'DatosArticulo' => function ($query) {
                                $query->select(['id', 'codigo', 'precio', 'descripcion', 'genero_id']);
                            }
                        ]);

                        $query->with([
                            'Talle' => function ($query) {
                                $query->select(['id', 'nombre']);
                            }
                        ]);
                    },
                    'Usuario' => function ($query) {
                        $query->select(['id', 'nombre', 'apellido']);
                    },
                    'Cliente' => function ($query) {
                        $query->select(['id', 'nombre', 'apellido', 'email', 'telefono']);
                    },
                    'Cambio' => function ($query) {
                        $query->select(['id', 'saldo', 'articulo_id', 'venta_id']);
                    }
                ]
            )
            ->select([
                'id', 'cantidad_articulos', 'monto_total', 'nro_orden',
                'cliente_id', 'user_id', 'created_at'
            ])
            ->firstOrFail();

        return $venta;
    }

    /* Mutators */
    public function setClienteIdAttribute($value)
    {
        $this->attributes['cliente_id'] = $value ?: null;
    }

    /**
     * RELACIONES
     **/

    public function Usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Local()
    {
        return $this->belongsTo(Local::class);
    }

    public function Cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function Articulos()
    {
        return $this->belongsToMany(Articulo::class, 'articulo_venta', 'venta_id', 'articulo_id')
            ->withPivot(['cantidad', 'precio', 'subtotal', 'descuento']);
    }

    public function Cambio()
    {
        return $this->hasOne(Cambio::class);
    }

    public function dameVentasParaGraficoDeHome($fecha_desde, $fecha_hasta)
    {
        // Inicializo el controller para poder acceder a las funciones
        $controller = new Controller();

        // Devolvemos la cantidad de ventas agrupadas por dia, desde el primer dia del mes hasta hoy
        // [date => 2016-11-11, ventas => 7]
        return
            $this->whereDate('created_at', '>=', $fecha_desde)
                ->whereDate('created_at', '<=', $fecha_hasta)
                ->where('local_id', $controller->getLocalId())
                ->select([
                    DB::raw('DATE(created_at) as dia'),
                    DB::raw('count(*) as cantidad')
                ])
                ->groupBy('dia')
                ->orderBy('created_at', 'ASC')
                ->get();
    }
}
