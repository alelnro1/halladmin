<?php

namespace App\Models\Mercaderia;

use App\Models\Local;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MercaderiaTemporal extends Model
{
    protected $table = "mercaderias_temporales";

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
