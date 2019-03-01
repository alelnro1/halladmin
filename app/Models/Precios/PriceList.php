<?php

namespace App\Models\Precios;

use App\Models\Local;
use App\Models\Negocio;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    protected $table = "price_list";

    protected $fillable = [
        'nombre', 'descripcion', 'vigencia_desde', 'vigencia_hasta', 'dias', 'es_default', 'activo',
        'local_id', 'negocio_id'
    ];

    public function PriceListEntries()
    {
        return $this->hasMany(PriceListEntry::class);
    }

    public function Negocio()
    {
        return $this->belongsTo(Negocio::class);
    }

    public function Local()
    {
        return $this->belongsTo(Local::class);
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getVigenciaDesde()
    {
        return $this->vigencia_desde;
    }

    public function getVigenciaHasta()
    {
        return $this->vigencia_hasta;
    }

    /**
     * Obtenemos la lista por default de un negocio
     *
     * @param $negocio_id
     * @return mixed
     */
    public static function getDefaultDeNegocio($negocio_id)
    {
        return
            self::where('negocio_id', $negocio_id)
                ->where('es_default', true)
                ->first();
    }

    /**
     * Obtengo la price list para un negicio
     */
    public static function getPriceListParaHoy($negocio_id = null, $local_id = null)
    {
        $hoy = Carbon::now()->format('Y-m-d H:i:s');

        // Busco por vigencia de fechas
        $pl_fechas =
            self::where('vigencia_desde', '>=', $hoy)
                ->where('vigencia_hasta', '<=', $hoy)

                // Busco por negocio o local
                ->where(function ($query) use ($negocio_id, $local_id) {
                    $query->where('negocio_id', $negocio_id)
                        ->orWhere('local_id', $local_id);
                })
                ->get();

        dump($pl_fechas);

        // Si hay fechas => filtro las que me devolvió por día
        if($pl_fechas) {
            // Hay días
            $dia_hoy = date('N');
        } else {
            // Si no hay fechas => busco por día

            //$pl_dias =
        }

        // Si hasta ahora no encontré ninguna PL => default
        $pl = self::getDefaultDeNegocio($negocio_id);

        return $pl;

    }
}
