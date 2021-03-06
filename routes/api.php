<?php

use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\CommandeController;
use App\Http\Controllers\API\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login_user',[UserController::class,'login_user']);
//CRUD UTILISATEURS
Route::resource('users',UserController::class);
//CRUD CLIENTS
Route::resource('clients',ClientController::class);
//CRUD COMMANDES
Route::resource('commandes',CommandeController::class);
