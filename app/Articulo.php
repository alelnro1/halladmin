<?php

namespace App;

use App\Http\Controllers\Controller;
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
                    'DatosArticulo' => function($query) {
                        $query->select(['id', 'codigo', 'precio', 'descripcion', 'genero_id']);
                    },
                    'Talle' => function($query) {
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
            ->withPivot(['costo', 'cantidad'])
            ->withTimestamps();
    }

    public function Ventas()
    {
        return $this->belongsToMany(Venta::class, 'articulo_venta', 'articulo_id', 'venta_id')
            ->withPivot(['cantidad', 'precio', 'subtotal', 'descuento']);
    }
}
