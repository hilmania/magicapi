<?php

use Illuminate\Http\Request;

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

// /Applications/MAMP/htdocs/wanotifapi/app/Providers/RouteServiceProvider.php:
//    66      protected function mapApiRoutes()
//    67      {
//    68:         Route::prefix('v1')

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware('auth:api')->get('/send_message', 'ApiController@sendmessage');

Route::get('/auth', 'ApiController@authStatus');
Route::post('/send', 'ApiController@sendMessage');
Route::get('/sendtest', 'ApiController@sendtestMessage');



