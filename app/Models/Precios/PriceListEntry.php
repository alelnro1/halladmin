<?php

namespace App\Models\Precios;

use Illuminate\Database\Eloquent\Model;

class PriceListEntry extends Model
{
    protected $table = "price_list_entry";

    public function PriceList()
    {
        return $this->belongsTo(PriceList::class);
    }
}
