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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::middleware('auth:sanctum')->post('api/vi/album', function (Request $request) {
//     return $request->user();
// });
Route::middleware('auth:sanctum')->group(function () { // auth by santum
    Route::prefix('v1')->group(function () {
        //api/v1/album
        Route::apiResource('album', App\Http\Controllers\V1\AlbumController::class); //end point will now be api/v1/album
        Route::get('image', [App\Http\Controllers\V1\ImageManipulationController::class, 'index']);
        Route::get('image/{id}', [App\Http\Controllers\V1\ImageManipulationController::class, 'show']);
        Route::get('image/by-album/{album}', [App\Http\Controllers\V1\ImageManipulationController::class, 'byAlbum']);
        Route::post('image/resize', [App\Http\Controllers\V1\ImageManipulationController::class, 'resize']);
        Route::delete('image/delete/{id}', [App\Http\Controllers\V1\ImageManipulationController::class, 'destroy']);
    });
});
