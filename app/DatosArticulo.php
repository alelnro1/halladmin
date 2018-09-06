<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class DatosArticulo extends Model
{
    protected $table = "datos_articulos";

    protected $fillable = [
        'codigo', 'precio', 'descripcion', 'categoria_id', 'genero_id', 'local_id'
    ];

    public static function getArticuloConCodigo($codigo)
    {
        $controller = new Controller();

        $articulo = DatosArticulo::where('codigo', $codigo)
            ->where('local_id', $controller->getLocalId())
            ->first();

        return $articulo;
    }

    public function Categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function Articulo()
    {
        return $this->hasMany(Articulo::class);
    }

    public function Genero()
    {
        return $this->belongsTo(Genero::class);
    }
}
