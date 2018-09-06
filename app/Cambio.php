<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cambio extends Model
{
    protected $table = 'cambios';

    protected $fillable = [
        'articulo_id', 'local_id', 'user_id', 'venta_id', 'saldo'

    ];

    public function Local()
    {
        return $this->belongsTo(Local::class);
    }

    public function Venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function Usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function Articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

    public function dameCambiosParaGraficoDeHome($fecha_desde, $fecha_hasta)
    {
        // Inicializo el controller para poder acceder a las funciones
        $controller = new Controller();

        // Devolvemos la cantidad de cambios agrupadas por dia, desde el primer dia del mes hasta hoy
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
