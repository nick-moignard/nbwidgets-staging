<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Factory\AbstractFactory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class LogController extends Controller
{

    private $factory;
    private $dao;
    public function __construct()
    {
        $this->factory = AbstractFactory::getFactory('DAO');
        $this->dao = $this->factory->getDAO('LogDao');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logs = $this->dao->select();
        return response()->json(['status' => 'ok', 'data' => $logs], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $log = $this->dao->delete($id);

        return response()->json(['status'=>'ok','data'=>$log],200);
    }

    public function download(Request $request,$id)
    {
        $log = $this->dao->find($id);
        //var_dump($log->dump_file);
        return Storage::disk('local')->download($log->dump_file);
    }

    public function restore(Request $request)
    {
        $log = $this->dao->find($request['id']);
        $db = Config::get('database.connections');
        $dbRoute  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $log->dump_file;
        $command='mysql -h' . $db['mysql']['host'] .' -u' . $db['mysql']['username'] .' --password="' . $db['mysql']['password'] .'" ' . $db['mysql']['database'] .' < ' . $dbRoute;
        $output = array();
        exec($command,$output,$worked);
        
        if($worked === 0){
            return response()->json(['success' => true, 'data' => 'The database restore process is complete'], 200);
        } else{
            return response()->json(['success' => false, 'data' => 'The database restore process is failed'], 500);
        }
    }
}
