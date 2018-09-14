<?php

namespace App\Models\Ventas;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VentaCancelada extends Model
{
    protected $table = "ventas_canceladas";

    protected $fillable = [
        'cliente_id', 'user_id', 'local_id', 'motivo'
    ];

    public function setClienteIdAttribute($value)
    {
        $this->attributes['cliente_id'] = $value ?: null;
    }

    public function Usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function dameVentasCanceladasParaGraficoDeHome($fecha_desde, $fecha_hasta)
    {
        // Inicializo el controller para poder acceder a las funciones
        $controller = new Controller();

        // Devolvemos la cantidad de ventas canceladas agrupadas por dia, desde el primer dia del mes hasta hoy
        // [date => 2016-11-11, cantidad => 7]
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
