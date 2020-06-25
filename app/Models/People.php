<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    protected $fillable= [
        'nation_id',
        'nation_tag',
        'number_page',
        'person_id',
        'first_name',
        'last_name',
        'industry',
        'city',
        'country',
        'state',
        'profile_image',
        'occupation',
        'employer',
        'email',
        'twitter',
        'linkedin',
        'facebook',
        'phone',
        'work_phone',
        'mobile',
        'primary_address',
        'secondary_address',
        'tertiary_address',
        'zip',
        'country_code',
        'tags'
    ];
}
