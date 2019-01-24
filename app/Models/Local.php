<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use App\Models\Mercaderia\Articulo;
use App\Models\Mercaderia\MercaderiaTemporal;
use App\Models\Ventas\Venta;
use App\Models\Ventas\VentaCancelada;
use App\Models\Ventas\VentaTemporal;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;

class Local extends Model
{
    use SoftDeletes;

    protected $table = 'locales';

    protected $fillable = [
        'nombre', 'descripcion', 'archivo', 'domicilio', 'email', 'telefono', 'negocio_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = ucfirst($value) ?: null;
    }

    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = ucfirst($value) ?: null;
    }

    public function Usuarios()
    {
        return $this->belongsToMany(User::class, 'local_usuario', 'local_id', 'user_id')
            ->withPivot('es_admin');
    }

    public function Categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_local', 'local_id', 'categoria_id');
    }

    public function MercaderiasTemporales()
    {
        return $this->hasMany(MercaderiaTemporal::class);
    }

    public function VentasTemporales()
    {
        return $this->hasMany(VentaTemporal::class);
    }

    public function VentasCanceladas()
    {
        return $this->hasMany(VentaCancelada::class);
    }

    public function Articulos()
    {
        return $this->hasMany(Articulo::class);
    }

    public function Ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function Cambios()
    {
        return $this->hasMany(Cambio::class);
    }

    public function Negocio()
    {
        return $this->belongsTo(Negocio::class);
    }

    public function Cajas()
    {
        return $this->hasMany(Caja::class);
    }

    public function Proveedores()
    {
        return $this->belongsToMany(Proveedor::class, 'local_proveedor', 'local_id', 'proveedor_id');
    }

    /**
     * Se obtiene la cantidad de ventas canceladas del local
     *
     * @return mixed
     */
    public function getCantidadDeVentas()
    {
        return $this->Ventas()->whereDate('created_at', '=', date("Y-m-d", time()))->count();
    }

    /**
     * Se obtiene la cantidad de ventas canceladas del local
     *
     * @return mixed
     */
    public function getCantidadDeVentasCanceladas()
    {
        return $this->VentasCanceladas()->whereDate('created_at', '=', date("Y-m-d", time()))->count();
    }

    /**
     * Se obtiene la cantidad de cambios del local
     *
     * @return mixed
     */
    public function getCantidadDeCambios()
    {
        return $this->Cambios()->whereDate('created_at', '=', date("Y-m-d", time()))->count();
    }

    /**
     * Se obtiene la cantidad de cambios del local
     *
     * @return int
     */
    public function getCantidadDeUsuarios(): int
    {
        return $this->Usuarios()->where('user_id', '<>', Auth::user()->id)->count();
    }

    public static function getLocalesConUsuarios()
    {
        return
            Local::whereHas(
                'Usuarios', function ($query) {
                $query->where('user_id', Auth::user()->id);
            }
            )
                ->select(['id', 'nombre', 'email', 'telefono'])
                ->get();
    }

    /**
     * Dado un articulo, verificamos si este pertenece al local actual
     *
     * @param $articulo
     * @return bool
     */
    public function articuloPerteneceALocal($articulo): bool
    {
        return $this->id == $articulo->getLocal();
    }

    /**
     * Obtenemos todos los usuarios del local solicitado, menos el usuario actual
     *
     * @param $local_id
     * @return mixed
     */
    public function getUsuarios()
    {
        // Obtenemos todos los usuarios del local
        $usuarios =
            $this->load([
                'Usuarios'
            ])
                ->Usuarios;

        // Eliminamos el usuario actual del listado
        $usuarios =
            $usuarios->filter(function ($usuario) {
                return $usuario->id != Auth::user()->id;
            });

        return $usuarios;
    }

    /**
     * Obtenemos las ventas del local, con la posibilidad de limitar los resultados
     *
     * @param null $limit
     * @return mixed
     */
    public function getVentas($limit = null)
    {
        $local_ventas =
            $this->load([
                'Ventas' => function ($query) use ($limit) {
                    $query->with('Usuario')
                        ->orderBy('created_at', 'desc')
                        ->limit($limit);
                }
            ]);

        return $local_ventas->Ventas;
    }

    /**
     * Obtenemos los articulos del local
     *
     * @return mixed
     */
    public function getArticulos()
    {
        return $this->Articulos;
    }

    /**
     * Obtenemos los proveedores del local
     *
     * @return mixed
     */
    public function getProveedores()
    {
        return $this->Proveedores;
    }

    /**
     * Obtenemos las cajas del local
     *
     * @return mixed
     */
    public function getCajas()
    {
        return $this->Cajas;
    }

    /**
     * Obtenemos la mercaderia del local
     *
     * @return mixed
     */
    public function getMercaderia()
    {
        $controller = new Controller();

        $mercaderia =
            Articulo::select(['id', 'talle_id', 'color', 'datos_articulo_id'])
                ->where('local_id', $controller->getLocalId());

        $mercaderia->load([
            'DatosArticulo' => function ($query) {
                $query->with('Categoria');
            },
            'Talle'
        ]);

        return $mercaderia;
    }

    /**
     * Obtenemos todos los cambios realizados en el local, para mostrar en la vista index de los cambios
     *
     * @return mixed
     */
    public function getCambiosParaIndexCambios()
    {
        $cambios = $this->Cambios;

        $cambios->load([
            'Venta' => function ($query) {
                $query->select(['id', 'nro_orden', 'monto_total', 'user_id']);
                $query->with(
                    [
                        'Articulos',

                        'Usuario' => function ($query) {
                            $query->select(['id', 'nombre', 'apellido']);
                        }
                    ]
                );
            },
            'Articulo' => function ($query) {
                $query->select(['id', 'datos_articulo_id']);
                $query->with(
                    [
                        'DatosArticulo' => function ($query) {
                            $query->select('id', 'descripcion');
                        }
                    ]
                );
            }
        ]);

        return $cambios;
    }
}
