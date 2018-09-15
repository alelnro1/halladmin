<?php

namespace App;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\Cambio;
use App\Models\Local;
use App\Models\Menu;
use App\Models\Mercaderia\MercaderiaTemporal;
use App\Models\Negocio;
use App\Models\UserLogin;
use App\Models\Ventas\Venta;
use App\Models\Ventas\VentaCancelada;
use App\Models\Ventas\VentaTemporal;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'apellido', 'telefono', 'email', 'password', 'es_admin', 'domicilio', 'negocio_id', 'caja_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public static function getAdministrador($id)
    {
        $administrador = User::findOrFail($id);

        $administrador->load([
            'Locales' => function ($query) {
                $query->withCount(
                    [
                        'Articulos', 'Usuarios', 'Ventas', 'Cambios'
                    ]
                );
            }
        ]);

        return $administrador;
    }

    public static function getAdministradores()
    {
        $administradores =
            User::where('es_admin', true)
                ->with('Logins')
                ->get();

        return $administradores;
    }

    /* Mutators */
    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = ucfirst($value) ?: null;
    }

    public function setApellidoAttribute($value)
    {
        $this->attributes['apellido'] = ucfirst($value) ?: null;
    }

    /**
     * Se verifica si el usuario actual es super admin
     *
     * @return bool
     */
    public function esSuperAdmin()
    {
        return $this->super_admin == true;
    }

    /**
     * Se verifica si el usuario actual es admin
     *
     * @return bool
     */
    public function esAdmin()
    {
        return $this->es_admin == true;
    }

    /**
     * Se verifica que el los modulos habilitados del usuario, tengan al modulo deseado
     *
     * @param  $nombre_modulo_deseado
     * @return bool
     */
    public function tieneModuloHabilitado($nombre_modulo_deseado)
    {
        // El super admin puede entrar a todos lados
        if (Auth::user()->esSuperAdmin()) {
            return true;
        }

        $modulos_habilitados = session('MODULOS_HABILITADOS');

        $habilitado = false;

        // Busco el modulo en los modulos habilitados
        foreach ($modulos_habilitados as $modulo_habilitado) {
            // Primero en el padre
            if ($modulo_habilitado->nombre == $nombre_modulo_deseado) {
                $habilitado = true;
                break;
            } else {
                // No encuentro en el padre, me fijo si tiene hijos (1 SOLO NIVEL)
                if (count($modulo_habilitado->MenusHijos) > 0) {
                    // Tiene hijos, busco en cada uno de ellos el modulo deseado
                    foreach ($modulo_habilitado->MenusHijos as $menu_hijo) {
                        if ($menu_hijo->nombre == $nombre_modulo_deseado) {
                            $habilitado = true;
                            break;
                        }
                    }
                }
            }
        }

        return $habilitado;
    }

    /**
     * Un usuario es sólo usuario cuando no es ni admin ni super admin
     *
     * @return bool
     */
    public function esUsuario()
    {
        return !$this->esSuperAdmin() && !$this->esAdmin();
    }

    /**
     * Se verifica si el usuario actual tiene algun local
     *
     * @return bool
     */
    public function tieneAlgunLocal()
    {
        // Si la sesion de locales es nula => vamos a ver si se creó algun local.
        // Este if se usa por si existe algun local, para no hacer la query
        if (session('HAY_ALGUN_LOCAL') == null || session('HAY_ALGUN_LOCAL') == false) {
            $local =
                Local::whereHas(
                    'Usuarios', function ($query) {
                        $query->where('user_id', Auth::user()->id);
                    }
                )->first();

            // Si hay locales del usuario => pongo la sesión en true
            if ($local) {
                $this->setearSesionTieneLocal();

                session(['LOCAL_ACTUAL' => $local]);
            }
        } else {
            // Si hay una sesion con un local pero no hay ningun local asignado, traigo el primero
            if (session('LOCAL_ACTUAL') == null) {
                $local =
                    Local::whereHas(
                        'Usuarios', function ($query) {
                            $query->where('user_id', Auth::user()->id);
                        }
                    )->first();

                session(['LOCAL_ACTUAL' => $local]);
            }
        }

        return session('HAY_ALGUN_LOCAL');
    }

    public function abrioCaja()
    {
        // Local actual
        $local_actual_id = session('LOCAL_ACTUAL')->id;

        // Buscamos la caja que abrio el usuario actual
        $caja = Caja::getCajaLocalUserActual($local_actual_id, $this->id);

        if ($caja) {
            return true;
        }

        return false;

        //return ($this->caja_id != null && session('LOCAL_ACTUAL')->);
    }

    public function cerroCaja()
    {
        $controller = new Controller();
        $caja = new Caja();

        return $caja->ultimaCajaCerrada($controller->getLocalId(), $this->id, 'cierre');
    }

    /**
     * El cliente abrio la caja => lo registro
     */
    public function registrarCaja($caja_id)
    {
        $this->caja_id = $caja_id;
        $this->save();
    }

    public function reiniciarAperturaCierreCaja()
    {
        $this->caja_id = null;
        $this->save();
    }

    /**
     * Se setea la sesión de que el usuario tiene al menos un local
     * Entonces puede hacer ingreso de mercaderia, ventas y cambios
     */
    public function setearSesionTieneLocal()
    {
        session(['HAY_ALGUN_LOCAL' => true]);
    }


    // Relaciones
    public function Locales()
    {
        return $this->belongsToMany(Local::class, 'local_usuario', 'user_id', 'local_id');
    }

    public function MercaderiaTemporal()
    {
        return $this->hasMany(MercaderiaTemporal::class);
    }

    public function VentaTemporal()
    {
        return $this->hasMany(VentaTemporal::class);
    }

    public function Negocio()
    {
        return $this->belongsTo(Negocio::class);
    }

    public function Ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function Menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_usuario', 'user_id', 'menu_id');
    }

    public function Cambios()
    {
        return $this->hasMany(Cambio::class);
    }

    public function VentasCanceladas()
    {
        return $this->hasMany(VentaCancelada::class);
    }

    public function Cajas()
    {
        return $this->hasMany(Caja::class);
    }

    public function Logins()
    {
        return $this->hasMany(UserLogin::class);
    }
}
