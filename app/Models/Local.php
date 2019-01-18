<?php

namespace App\Models;

use App\Models\Mercaderia\Articulo;
use App\Models\Mercaderia\MercaderiaTemporal;
use App\Models\Ventas\Venta;
use App\Models\Ventas\VentaCancelada;
use App\Models\Ventas\VentaTemporal;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Obtenemos todos los usuarios del local solicitado, menos
     *
     * @param $local_id
     * @return mixed
     */
    public function getUsuarios()
    {
        // Obtenemos todos los usuarios del local
        $usuarios =
            self::where('id', $this->id)
                ->with('Usuarios')
                ->first()
                ->Usuarios;

        // Eliminamos el usuario actual del listado
        $usuarios =
            $usuarios->filter(function ($usuario) {
                return $usuario->id != Auth::user()->id;
            });

        return $usuarios;
    }

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
    public function cantidadDeVentas()
    {
        return $this->Ventas()->whereDate('created_at', '=', date("Y-m-d", time()))->count();
    }

    /**
     * Se obtiene la cantidad de ventas canceladas del local
     *
     * @return mixed
     */
    public function cantidadDeVentasCanceladas()
    {
        return $this->VentasCanceladas()->whereDate('created_at', '=', date("Y-m-d", time()))->count();
    }

    /**
     * Se obtiene la cantidad de cambios del local
     *
     * @return mixed
     */
    public function cantidadDeCambios()
    {
        return $this->Cambios()->whereDate('created_at', '=', date("Y-m-d", time()))->count();
    }

    /**
     * Se obtiene la cantidad de cambios del local
     *
     * @return mixed
     */
    public function cantidadDeUsuarios()
    {
        return $this->Usuarios()->where('user_id', '!=', Auth::user()->id)->count();
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
    public function articuloPerteneceALocal($articulo)
    {
        return $this->id == $articulo->getLocal();
    }
}
