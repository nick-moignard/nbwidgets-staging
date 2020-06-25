<?php

namespace App\Libraries\Dao;

use App\Models\Nation;
use App\Models\NationDetails;
use App\Models\People;
use App\Models\NationPages;
use Illuminate\Support\Facades\DB;

class NationDao
{
    public function select()
    {
        //$nations = Nation::select('nation_details.hq','nations.*')->join('nation_details','nations.id','=','nation_details.nation_id')->where('nations.status', '1')->distinct()->get();
        $nations = Nation::where('nations.status', '1')->get();
        return $nations;
    }
    public function insert($request)
    {
        //$nation = Nation::updateOrCreate(['slug' => $request['slug'],'status' => 1], $request);
        $nation = Nation::create($request);
        return $nation;
    }

    public function get($id)
    {
        $nation = Nation::where('id', $id)->where('status', '1')->get();
        return $nation;
    }

    public function first($id)
    {
        $nation = Nation::where('id', $id)->where('status', '1')->first();
        return $nation;
    }

    public function get_hq_nations()
    {
        $nations = Nation::where('status', '1')
            ->join('nation_details', 'nation_details.nation_id', '=', 'nations.id')
            ->where('hq', 1)
            ->get();

        return $nations;
    }
    public function update($request, $id)
    {
        $nation = Nation::find($id);

        return $nation->update($request);
    }
    public function delete($id)
    {
        $nation = Nation::find($id);

        return $nation->update(['status' => 0]);
    }

    public function exists($slug)
    {
        return Nation::where('slug', $slug)->exists();
    }

    // public function deleteCache($id){
    //     $nation = Nation::find($id);
    //     $tag = $nation->nation_details->tag;
    //     $people = People::where([['nation_id', $nation->id], ['nation_tag', $tag],['actual',0]]);
    //     $pages = NationPages::where([['nation_id', $nation->id], ['nation_tag', $tag],['actual',0]]);
    //     $people->delete();
    //     $pages->delete();
    // }

    public function syncNations(){
        $nations = Nation::where('status', '1')
            ->join('nation_details', 'nation_details.nation_id', '=', 'nations.id')
            ->where('membership_sync','!=','')
            ->get();

        return $nations;
    }

    public function getNationBySlug($slug){
        $nation = Nation::where('slug', $slug)
            ->join('nation_details', 'nation_details.nation_id', '=', 'nations.id')
            ->get()
            ->first();

        return $nation;
    }

    public function getAllNationCache($nation_id,$tag,$forum,$industry){
        $where[] = ['nation_id',$nation_id];
        $where[] = ['nation_tag',$tag];
        if($forum) $where[] = ['tags','like','%'.json_encode($forum).'%'];
        if($industry) $where[] = ['tags','industry','%'.json_encode($industry).'%'];
        /*if($forum){
            $nations = People::where([['nation_id',$slug],['nation_tag',$tag],['tags','like','%'.json_encode($forum).'%']])
            ->orderBy('last_name', 'asc')
            ->get();
        }
        else{
            $nations = People::where([['nation_id',$slug],['nation_tag',$tag]])
            ->orderBy('last_name', 'asc')
            ->get();
        }*/
        $people = People::where($where)
            ->orderBy('last_name', 'asc')
            ->get();
        return $people;
    }

    public function getAllNationCacheByPage($slug,$tag,$page){

        $nations = People::where(
                        [['nation_id',$slug],['nation_tag',$tag]])
                    ->orderBy('last_name', 'ASC')
                    ->limit(50*$page)
                    ->get();

        return $nations;

    }

    // public function deactivatePeople($nation_id,$nation_tag){
    //     return People::where([['nation_id',$nation_id],['nation_tag',$nation_tag]])
    //                 ->update(['actual' => 0]);
    // }
}
