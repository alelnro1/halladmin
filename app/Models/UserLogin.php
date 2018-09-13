<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    protected $table = 'logins';

    protected $fillable = [
        'user_id', 'agent', 'ip'
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
