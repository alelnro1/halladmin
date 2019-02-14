<?php

namespace App\Models\Mercaderia;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Negocio;
use Illuminate\Database\Eloquent\Model;

class DatosArticulo extends Model
{
    protected $table = "datos_articulos";

    protected $fillable = [
        'codigo', 'precio', 'descripcion', 'categoria_id', 'genero_id', 'local_id', 'negocio_id'
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

    public function Negocio()
    {
        return $this->belongsTo(Negocio::class);
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getNombreGenero()
    {
        return $this->Genero->getNombre();
    }

    public function getNombreCategoria()
    {
        return $this->Categoria->getNombre();
    }
}
