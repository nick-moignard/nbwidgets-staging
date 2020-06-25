<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Renovate extends Model
{
    protected $fillable= [
        'nation_id',
        'user_id',
        'execute',
        'no_members',
        'no_nomembers',
        'next_url',
        'slug',
        'access_token'
    ];
}
