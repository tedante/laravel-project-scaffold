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

Route::prefix('v1')->group(function() {

    Route::post('upload', 'StorageController@upload');

    Route::middleware('json')->group(function() {
        Route::middleware('auth:api')->group(function() {
            Route::post('login', 'AuthController@login')->name('login');
            Route::resource('materials', 'MaterialController');
            Route::get('materials/export/excel', 'MaterialController@export');
            Route::post('materials/import/excel', 'MaterialController@import');
            Route::post('orders/import/excel', 'OrderController@import');
        });
    });

});
