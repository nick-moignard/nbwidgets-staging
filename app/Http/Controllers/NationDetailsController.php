<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Factory\AbstractFactory;
use App\Models\Log;

class NationDetailsController extends Controller
{
    private $factory;
    private $dao;
    private $logDao;
    public function __construct()
    {
        $this->factory = AbstractFactory::getFactory('DAO');
        $this->dao = $this->factory->getDAO('NationDetailsDao');
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
        return response()->json(['status' => 'ok', 'data' => $nations], 200);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\nation_details  $nation_details
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $nations = $this->dao->get($id);
        $hq_nations = $this->dao->get_hq_nations();
        $hq_pictures = $this->dao->get_hq_pictures();
        
        $nation_total = AbstractFactory::getFactory('DAO')->getDAO('PeopleDao')->get_count_by_tag($nations->first()->tag);
        //$data['hq_pic_nation'] = $this->MNation->getAllSyncPicNations();
        return response()->json(['status' => 'ok', 'data' => [ $nations,$hq_nations,$hq_pictures, $nation_total]], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\nation_details  $nation_details
     * @return \Illuminate\Http\Response
     */
    public function edit(nation_details $nation_details)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\nation_details  $nation_details
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $nation = $this->dao->update($request->nation, $id);
        $nation = $this->dao->get($id)[0];
        //Log::create(["user_id" => $request->user_id, "nation_id" => $nation->id, 'description' => 'Update Nation "' . $nation->name . '"']);
        $this->logDao->create($request->user_id,$nation->id,'Update Nation "'.$nation->name.'"');

        return response()->json(['status' => 'ok', 'data' => $nation], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\nation_details  $nation_details
     * @return \Illuminate\Http\Response
     */
    public function destroy(nation_details $nation_details)
    {
        //
    }
}
