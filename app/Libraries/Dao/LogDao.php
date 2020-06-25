<?php

namespace App\Libraries\Dao;

use App\Models\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Spatie;

class LogDao
{

    public function create($user_id,$nation_id,$description)
    {
        $file = 'dumps/'.time().'.sql';
        $filePath = Storage::disk('local')->path($file);
        $db = Config::get('database.connections');

        Spatie\DbDumper\Databases\MySql::create()
            ->setDbName($db['mysql']['database'])
            ->setUserName($db['mysql']['username'])
            ->setPassword($db['mysql']['password'])
            ->dumpToFile($filePath);

        //Storage::put($file, 'select 1');
        Log::create(["user_id"=>$user_id,"nation_id"=>$nation_id,'description'=>$description,'dump_file'=>$file]);
    }

    public function find($id)
    {
        $log = Log::find($id);

        return $log;
    }


    public function select()
    {
       $logs = Log::join('users','users.id','=','logs.user_id')
                    ->leftJoin('nations','nations.id','=','logs.nation_id')
                    ->select('logs.*','users.name as user_name','nations.name as nation_name')
                    ->orderBy('created_at', 'DESC')
                    ->get();
        return $logs;
    }
    public function insert($request)
    {
        $log = Log::updateOrCreate(['slug'=>$request['slug']],$request);

        return $log;
    }

    public function get($id)
    {
        $log = Log::where('id', $id)->get();

        return $log;
    }


    public function update($request, $id)
    {
        $log = Log::find($id);

        return $log->update($request);
    }
    public function delete($id)
    {
        return Log::find($id)->delete();
    }
}
