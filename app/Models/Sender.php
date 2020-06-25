<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sender extends Model
{
    protected $fillable= [
        'nation_id',
        'user_id',
        'manual',
        'execute',
        'current',
        'page',
        'tag',
        'slug',
        'access_token'
    ];
}
