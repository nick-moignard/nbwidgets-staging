<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{

    public function __construct()
    {
        ini_set('max_execution_time', 1000);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /*
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return view('layouts.app');
    }

    public function authcode_callback(Request $request)
    {
    }

    public function widget($id, $theme, $show)
    {
        $widget = Storage::disk('local')->get('widget.txt');
        $widget = str_replace("%%%URL_SITE%%%",Config::get('app.url'),$widget);
        $widget = str_replace('%%%NATION_ID%%%', $id, $widget);
        $widget = str_replace('%%%SHOW_SEARCH%%%', '"'.$show.'"', $widget);
        $widget = str_replace('%%%THEME%%%', '"'.$theme.'"', $widget);
        return Response::make($widget, '200')->header('Content-Type', 'text/javascript');
    }
}
