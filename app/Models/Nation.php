<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nation extends Model
{
    protected $table = "nations";
    protected $fillable=['name','slug','access_token','logo','people_count','status'];

    public function logs()
    {
        return $this
            ->hasMany('App\Models\Log');
    }

    public function nation_details()
    {
        return $this
            ->hasOne('App\Models\NationDetails');
    }
}
