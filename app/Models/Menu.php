<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = "menus";
    
    public function MenuPadre()
    {
        return $this->hasOne(Menu::class, 'id', 'padre_id');
    }
    
    public function MenusHijos()
    {
        return $this->hasMany(Menu::class, 'padre_id', 'id');
    }

    public function Usuarios()
    {
        return $this->belongsToMany(User::class, 'menu_usuario', 'menu_id', 'user_id');
    }
}
