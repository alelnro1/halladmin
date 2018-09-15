<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = "cajas";

    protected $fillable = ['local_id', 'user_id', 'apertura', 'cierre', 'hora_apertura', 'hora_cierre'];

    public function Local()
    {
        return $this->belongsTo(Local::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Devuelve true si el ultimo registro está abierto y cerrado
     * @param $local_id
     * @param $user_id
     * @param string $tipo
     */
    public function ultimaCajaCerrada($local_id, $user_id, $tipo)
    {
        $ultimo_registro =
            $this->where('local_id', $local_id)
                ->where('user_id', $user_id)
                ->orderBy('created_at', 'DESC')
                ->take(1)
                ->first();


        if ($ultimo_registro) {
            if ($ultimo_registro->apertura != "" && $ultimo_registro->cierre != "") {
                return true;
            }
        }

        return false;
    }

    public static function getCajaLocalUserActual($local_actual_id, $user_id)
    {
        $caja =
            self::where('local_id', $local_actual_id)
                ->where('user_id', $user_id)
                ->where('cierre', null)
                ->first();

        return $caja;
    }

    /**
     * Devuelve el ID si el ultimo registro no está completo (apertura y cierre completados). Sino devuelve false
     * @param $local_id
     * @param $user_id
     * @param string $tipo
     */
    public function getUltimaCajaSinCerrar($local_id, $user_id, $tipo)
    {
        $ultimo_registro =
            $this->where('local_id', $local_id)
                ->where('user_id', $user_id)
                ->whereNull($tipo)
                ->orderBy('created_at', 'DESC')
                ->take(1)
                ->first();


        if ($ultimo_registro) {
            if ($ultimo_registro->apertura != "" && $ultimo_registro->cierre != "") {
                return fale;
            }
        }

        return $ultimo_registro;
    }

    /**
     * Se abre o se cierra la caja
     * @param $local_id
     * @param $user_id
     * @param $monto
     * @param $tipo
     * @return Caja
     */
    public function registrarTransaccion($local_id, $user_id, $monto, $tipo)
    {
        $ultimo_registro = $this->getUltimaCajaSinCerrar($local_id, $user_id, $tipo);
        $horario_tipo = 'date_' . $tipo;

        // Si no hay ningun registro sin cerrar => lo creo
        if (!$ultimo_registro) {
            $this->local_id = $local_id;
            $this->user_id = $user_id;
            $this->$tipo = $monto;
            $this->$horario_tipo = date("Y-m-d H:i:s", time());

            $this->save();

            $caja = $this;
        } else {
            // Me dio una fila => la guardo
            $ultimo_registro->$tipo = $monto;
            $ultimo_registro->$horario_tipo = date("Y-m-d H:i:s", time());

            $ultimo_registro->save();

            $caja = $ultimo_registro;
        }

        return $caja;
    }
}
