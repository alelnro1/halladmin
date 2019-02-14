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
        'articulo_id', 'precio', 'es_absoluto'
    ];

    public function getPrecio()
    {
        return $this->precio;
    }

    public function PriceList()
    {
        return $this->belongsTo(PriceList::class);
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
                $hoy = Carbon::createFromFormat('Y-m-d H:i:s', time());

                // Guardar en historico el precio que está ahora y crear el nuevo (desde, hasta) y borrar el que está
                PLEHistorico::create([
                    'vigencia_desde' => $pl_entry->created_at,
                    'vigencia_hasta' => $hoy,
                    'precio' => $pl_entry->getPrecio(),
                ]);

                // Creamos el nuevo ple
                self::create([
                    'articulo_id' => $articulo->id,
                    'price_list_id' => $default_price_list->id,
                    'precio' => $precio
                ]);

                // Eliminamos la ple vieja
                $pl_entry->delete();
            }
        } else {
            // Nunca se creo un PLE para este producto
            self::create([
                'articulo_id' => $articulo->id,
                'precio' => $precio
            ]);
        }
    }
}
