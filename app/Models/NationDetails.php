<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NationDetails extends Model
{
    protected $table = "nation_details";
    protected $fillable=['theme','tag','nation_id','show_options','intro','disclaimer','report_color','hq','membership_sync','sync_picture','picture_sync'];
}
