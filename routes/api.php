<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// API Routes

// Routes for Authentication trough API and JWT

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group([
    'middleware' => 'auth:api',
], function(){
});

// Routes to nations resources

Route::resource('/nations', 'NationController');
Route::resource('/nation/details', 'NationDetailsController');

// Routes to list and delete log entries

Route::get('/logs', 'LogController@index');
Route::delete('/logs/{log}', 'LogController@destroy');
Route::get('/logs/download/dump/{log}', 'LogController@download');
Route::post('/logs/restore', 'LogController@restore');


// Routes that connect with NationBuilderAPI

Route::post('/nation/generate/token', 'NationBuilderApiController@generate_token');
Route::post('/nation/clear/cache', 'NationBuilderApiController@clear_cache');
Route::post('/nation/update/members', 'NationBuilderApiController@update_sync_members');
Route::post('/nation/update/match/person', 'NationBuilderApiController@update_match_person');
Route::post('/nation/sync/member/log','NationBuilderApiController@create_sync_member_log');
Route::post('/nation/sync/imagen', 'PeopleController@syncNationPictures');
Route::get('/nation/activate/{id}', 'NationBuilderApiController@activate');

Route::get('/users','UserController@index');
Route::post('/users','UserController@store');
Route::get('/users/edit/{id}','UserController@edit');
Route::post('/users/edit/{id}','UserController@update');
Route::delete('/users/{user}','UserController@destroy');
Route::get('/roles','RoleController@index');

Route::post('/getAllPeopleList','NationBuilderApiController@getAllPeopleList');
Route::post('/getPeopleList','NationBuilderApiController@getPeopleList');
Route::post('/getPersonDetail','NationBuilderApiController@getPersonDetail');
Route::post('/getPDFDetail','NationBuilderApiController@getPDFDetail');

Route::post('/update/image', 'NationBuilderApiController@update_image');
Route::post('/update/logo', 'NationBuilderApiController@update_logo');
Route::post('/getPDFLogo/','NationBuilderApiController@getPDFLogo');
