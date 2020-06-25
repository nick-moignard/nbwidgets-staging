<?php

namespace App\Http\Controllers;

use App\Libraries\Factory\AbstractFactory;
use App\Http\Requests\NationRequest;
use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\NationDetails;

class NationController extends Controller
{
    private $factory;
    private $dao;
    private $logDao;
    public function __construct()
    {
        $this->factory = AbstractFactory::getFactory('DAO');
        $this->dao = $this->factory->getDAO('NationDao');
        $this->logDao = $this->factory->getDAO('LogDao');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nations = $this->dao->select();
        return response()->json(['status'=>'ok','data'=>$nations],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     *
     * Returns true if the nation with the received slug exists.
     *
     * @return \Illuminate\Http\Response
     */
    public function exists($slug)
    {
        $exists = $this->dao->exists($slug);
        return response()->json(['status'=>'ok','exists'=>$exists],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nation = $this->dao->insert($request->nation);

        if ($nation->id) {
            //Log::create(["user_id"=>$request->user_id,"nation_id"=>$nation->id,'description'=>'Add new Nation "'.$nation->name.'"']);
            $this->logDao->create($request->user_id,$nation->id,'Add new Nation "'.$nation->name.'"');
            NationDetails::create(['tag' => '', 'nation_id' => $nation->id]);
        }
        return response()->json(['status'=>'ok','data'=>$nation],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$hq_nations = $this->dao->get_hq_nations();

        $nation = $this->dao->get($id);


        return response()->json(['status'=>'ok','data'=>['nation' => $nation,'hq_nations' => $hq_nations]],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NationRequest $request, $id)
    {
        $nation = $this->dao->update($request->nation,$id);

        return response()->json(['status'=>'ok','data'=>$nation],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $temp_nation = $this->dao->get($id)[0];
        $nation_id = $temp_nation->id;
        $nation_name = $temp_nation->name;
        $nation = $this->dao->delete($id);

        //Log::create(["user_id" =>$request->all()['user_id'], "nation_id" => $nation_id, 'description' => 'Nation Deleted "' . $nation_name . '"']);
        $this->logDao->create($request->all()['user_id'],$nation_id,'Nation Deleted "'.$nation_name.'"');

        return response()->json(['status'=>'ok','data'=>null],200);
    }
}
