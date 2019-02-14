<?php

namespace App\Models\Mercaderia;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class LocalStock extends Model
{
    protected $table = "local_stock";

    protected $fillable = [
        'stock',
        'articulo_id',
        'local_id'
    ];

    /**
     * Ingresamos el stock del producto al local
     *
     * @param Articulo $articulo
     * @param $cantidad
     */
    public static function ingresarStockArticulo(Articulo $articulo, $cantidad)
    {
        $controller = new Controller();

        // Buscamos el producto para ver cuanto stock tien
        $stock_producto =
            self::where('articulo_id', $articulo->id)
                ->where('local_id', $controller->getLocalId())
                ->select(['stock'])
                ->first();

        if ($stock_producto) {
            $stock_producto->update([
                'stock' => $stock_producto->stock + $cantidad
            ]);
        } else {
            // No existe lo creo
            self::create([
                'stock' => $cantidad,
                'articulo_id' => $articulo->id,
                'local_id' => $controller->getLocalId()
            ]);
        }
    }
}
