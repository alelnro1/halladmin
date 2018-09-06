<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VentaTemporal extends Model
{
    protected $table = "ventas_temporales";

    protected $fillable = [
        'local_id', 'user_id', 'link'
    ];

    /** RELACIONES **/

    public function Usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function Local()
    {
        return $this->belongsTo(Local::class);
    }
}
