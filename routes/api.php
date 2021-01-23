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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('user', 'Dashboard\UserController');
Route::resource('state', 'Dashboard\StateController');
Route::resource('town', 'Dashboard\TownController');
Route::resource('unit', 'Dashboard\UnitController');
Route::resource('zone', 'Dashboard\ZoneController');
Route::resource('client', 'Dashboard\ClientController');

