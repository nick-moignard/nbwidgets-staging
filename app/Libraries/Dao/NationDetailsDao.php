<?php

namespace App\Libraries\Dao;

use App\Models\NationDetails;
use App\Models\Nation;

class NationDetailsDao
{
    public function select()
    {
        $nations = NationDetails::join('nations', 'nations.id', '=', 'nation_details.nation_id')->select()->get();
        return $nations;
    }
    public function insert($request)
    {
        $nation = NationDetails::create($request);

        return $nation;
    }

    public function get($id)
    {
        $nation = Nation::leftjoin('nation_details', 'nations.id','=', 'nation_details.nation_id')
                    ->select('nation_details.*','nations.*')
                    ->where('nations.id',$id)->get();

        return $nation;
    }
    public function update($request, $id)
    {
        $nation = NationDetails::find($id);
        Nation::find($request['nation_id'])->update($request);

        return $nation->update($request);
    }
    public function get_hq_nations()
    {
        $nations = Nation::where('status', '1')
            ->join('nation_details', 'nation_details.nation_id', '=', 'nations.id')
            ->where('hq','=', 1)
            ->get();

        return $nations;    
    }
    public function get_hq_pictures()
    {
        $nations = Nation::where('status', '1')
            ->join('nation_details', 'nation_details.nation_id', '=', 'nations.id')
            ->where('sync_picture','=', 1)
            ->get();

        return $nations;    
    }
}
