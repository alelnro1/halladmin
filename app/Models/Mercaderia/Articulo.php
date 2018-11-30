<?php

namespace App\Models\Mercaderia;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Local;
use App\Models\Proveedor;
use App\Models\Ventas\Venta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Articulo extends Model
{
    use SoftDeletes;

    protected $table = 'articulos';

    protected $fillable = [
        'archivo', 'local_id', 'talle_id', 'cantidad', 'color', 'datos_articulo_id', 'proveedor_id'
    ];

    protected $guarded = [
        'cantidad_a_vender'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public static function getArticulos()
    {
        $controller = new Controller();

        $articulos = Articulo::whereHas(
            'DatosArticulo', function ($query) use ($controller) {
            $query->where('local_id', $controller->getLocalId());
        })->get();

        $articulos->load([
            'DatosArticulo' => function ($query) {
                $query->with([
                    'Genero', 'Categoria'
                ]);
            },
            'Talle'
        ]);

        return $articulos;
    }

    public static function getArticulo($id)
    {
        $articulo = Articulo::findOrFail($id);

        $articulo->load([
            'Proveedores',
            'DatosArticulo' => function ($query) {
                $query->with([
                    'Articulo' => function ($query) {
                        $query->with(
                            [
                                'Talle' => function ($query) {
                                    $query->select(['id', 'nombre']);
                                }
                            ]
                        );

                        $query->select(['id', 'cantidad', 'color', 'talle_id', 'datos_articulo_id']);
                    }
                ]);
            }
        ]);

        return $articulo;
    }

    public static function getArticulosParaVentaForm()
    {
        $controller = new Controller();

        $articulos = Articulo::whereHas('DatosArticulo', function ($query) use ($controller) {
            $query->where('local_id', $controller->getLocalId());
        })->get();

        $articulos->load([
            'Talle' => function ($query) {
                $query->select(['id', 'nombre']);
            },
            'DatosArticulo' => function ($query) {
                $query->select(['id', 'codigo', 'descripcion', 'precio', 'genero_id']);

                $query->with('Genero');
            }
        ]);

        return $articulos;
    }

    public static function filtrarArticuloDeDatosId($datos_articulo, $articulo_temporal)
    {
        $articulo =
            Articulo::where('datos_articulo_id', $datos_articulo->id)
                ->where('color', $articulo_temporal['color'])
                ->whereHas('Talle', function ($query) use ($articulo_temporal) {
                    $query->where('nombre', $articulo_temporal['talle']);
                })
                ->with([
                    'DatosArticulo' => function ($query) {
                        $query->select(['id', 'codigo', 'precio', 'descripcion', 'genero_id']);
                    },
                    'Talle' => function ($query) {
                        $query->select(['id', 'nombre']);
                    }
                ])
                ->first();

        return $articulo;
    }

    public function setCodigoAttribute($value)
    {
        $this->attributes['codigo'] = strtoupper($value) ?: null;
    }

    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = ucfirst($value) ?: null;
    }

    public function Talle()
    {
        return $this->belongsTo(Talle::class);
    }

    public function Local()
    {
        return $this->belongsTo(Local::class);
    }

    public function Ofertas()
    {
        return $this->belongsToMany(Oferta::class, 'articulo_oferta', 'articulo_id', 'oferta_id');
    }

    public function Categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function DatosArticulo()
    {
        return $this->belongsTo(DatosArticulo::class);
    }

    public function Proveedores()
    {
        return $this->belongsToMany(Proveedor::class, 'articulo_proveedor', 'articulo_id', 'proveedor_id')
            ->withPivot(['costo', 'cantidad', 'cantidad_remanente'])
            ->withTimestamps();
    }

    public function Ventas()
    {
        return $this->belongsToMany(Venta::class, 'articulo_venta', 'articulo_id', 'venta_id')
            ->withPivot(['cantidad', 'precio', 'subtotal', 'descuento']);
    }

    /**
     * Obtenemos el id del local de donde es el artículo
     *
     * @return mixed
     */
    public function getLocal()
    {
        return $this->local_id;
    }

    public function getCodigo()
    {
        return $this->DatosArticulo->codigo;
    }

    public function getDescripcion()
    {
        return $this->DatosArticulo->descripcion;
    }

    public function getPrecio()
    {
        return $this->DatosArticulo->getPrecio();
    }

    public function getNombreGenero()
    {
        return $this->DatosArticulo->getNombreGenero();
    }

    public function getNombreCategoria()
    {
        return $this->DatosArticulo->getNombreCategoria();
    }

    public function getNombreTalle()
    {
        return $this->Talle->getNombre();
    }

    /**
     * Armamos un listado ordenado por FIFO para los ingresos de un articulo
     * Sacamos los que ya fueron consumidos
     *
     * @return mixed
     */
    public function getIngresosPorProveedorFIFO()
    {
        $ingresos =
            $this->Proveedores()->get()
                ->sortByDesc(function ($item) {
                    return $item->pivot->created_at;
                })
                ->filter(function ($item) {
                    dump($item->pivot);
                    return $item->pivot->cantidad_remanente > 0;
                });


        return $ingresos;
    }

    /**
     * En base a la cantidad de productos que se quieren vender, vamos a restar la cantidad restante de cada ingreso
     * por cada proveedor
     *
     * @param $cantidad_venta
     */
    public function actualizarCantidadesDeProveedores($cantidad_venta)
    {
        // Vamos a crear el listado FIFO
        $ingresos = $this->getIngresosPorProveedorFIFO();

        // Vamos a sacar la cantidad que estamos vendiendo de los proveedores del articulo por FIFO
        foreach ($ingresos as $ingreso) {
            if ($cantidad_venta > 0) {

                if ($ingreso->pivot->cantidad_remanente - $cantidad_venta <= 0) {
                    $cantidad_venta = $cantidad_venta - $ingreso->pivot->cantidad_remanente;

                    $ingreso->pivot->cantidad_remanente = 0;
                    $ingreso->pivot->save();

                } else {
                    $ingreso->pivot->cantidad_remanente = $ingreso->pivot->cantidad_remanente - $cantidad_venta;
                    $ingreso->pivot->save();

                    // Terminamos => Salimos
                    break;
                }
            }
        }
    }
}
