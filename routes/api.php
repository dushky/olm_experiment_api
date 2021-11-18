<?php

use App\Http\Controllers\Test;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Events\DataBroadcaster;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/', [Test::class, 'index']);
Route::get('/broadcast', function () {
    for($i = 0; $i <= 30; $i++) {
        usleep(500000);
        broadcast(new DataBroadcaster(rand(0,2)));
    }
    
});

