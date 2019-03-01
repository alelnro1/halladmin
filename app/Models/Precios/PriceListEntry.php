<?php

namespace App\Models\Precios;

use App\Http\Controllers\Controller;
use App\Models\Mercaderia\Articulo;
use App\Models\Mercaderia\PLEHistorico;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PriceListEntry extends Model
{
    protected $table = "price_list_entry";

    protected $fillable = [
        'articulo_id', 'precio', 'es_absoluto', 'price_list_id'
    ];

    public function getPrecio()
    {
        return $this->precio;
    }

    public function PriceList()
    {
        return $this->belongsTo(PriceList::class);
    }

    public function Articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

    /**
     * Creacion de una PLE para un producto desde el ingreso de mercaderia
     *
     * @param Articulo $articulo
     * @param $precio
     */
    public static function crearDefaultParaArticulo(Articulo $articulo, $precio)
    {
        $controller = new Controller();

        // Obtenemos el default price list del negocio
        $default_price_list = $controller->getNegocio()->getDefaultPriceList();

        // Buscamos el articulo en la lista de price entries del negocio
        $pl_entry =
            self::where('price_list_id', $default_price_list->id)
                ->where('articulo_id', $articulo->id)
                ->first();

        if ($pl_entry) {

            // Verificamos que el precio de la bd sea o no el mismo que el que recibimos
            if ($pl_entry != $precio) {
                $hoy = Carbon::now()->format('Y-m-d H:i:s');

                // Guardar en historico el precio que está ahora y crear el nuevo (desde, hasta) y borrar el que está
                PLEHistorico::create([
                    'vigencia_desde' => $pl_entry->created_at,
                    'vigencia_hasta' => $hoy,
                    'precio' => $pl_entry->getPrecio(),
                    'articulo_id' => $articulo->id,
                ]);

                // Creamos el nuevo ple
                self::create([
                    'articulo_id' => $articulo->id,
                    'price_list_id' => $default_price_list->id,
                    'precio' => $precio,
                    'es_absoluto' => true
                ]);

                // Eliminamos la ple vieja
                $pl_entry->delete();
            }
        } else {
            // Nunca se creo un PLE para este producto
            self::create([
                'articulo_id' => $articulo->id,
                'precio' => $precio,
                'price_list_id' => $default_price_list->id,
                'es_absoluto' => true
            ]);
        }
    }

    /**
     * Obtenemos todas las PLE donde figura el articulo
     *
     * @param $articulo_id
     * @return mixed
     */
    public static function getPLEntriesParaArticulo($articulo_id)
    {
        // Buscamos todas las PLE del articulo
        $pl_entries =
            self::where('articulo_id', $articulo_id)
                ->with('PriceList')
                ->get();

        return $pl_entries;
    }

    /**
     * Obtenemos el PLE de un articulo
     *
     * @param $articulo_id
     */
    public static function getPLEDefaultParaArticulo($articulo_id)
    {
        $pl =
            self::where('articulo_id', $articulo_id)
                ->whereHas('PriceList', function ($query) {
                    $query->where('es_default', true);
                })
        ->first();

        return $pl;
    }
}
