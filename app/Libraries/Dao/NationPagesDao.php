<?php

namespace App\Libraries\Dao;

use App\Models\NationPages;

class NationPagesDao
{
    public function select()
    {
        $nations = NationPages::where('status', '1')->get();

        return $nations;
    }
    public function insert($request)
    {
        $nation = NationPages::create($request);

        return $nation;
    }

    public function get($id)
    {
        $nation = NationPages::where('id', $id)->where('status', '1')->get();

        return $nation;
    }
    public function update($request, $id)
    {
        $nation = NationPages::find($id);
        
        return $nation->update($request);
    }
    public function delete($id)
    {
        $nation = NationPages::find($id);

        return $nation->update(['status' => 0]);
    }

    public function exists($slug)
    {
        return NationPages::where('nation_id', $slug)->exists();
    }

    public function deactivatePages($nation_id,$nation_tag){
        return NationPages::where([['nation_id',$nation_id],['nation_tag',$nation_tag]])
                    ->update(['actual' => 0]);
    }
}
