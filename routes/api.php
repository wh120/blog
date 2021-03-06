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

//images
Route::get('/photo/{path?}', 'APIController@viewphoto')->where('path', '(.*)');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::resource('categories', App\Http\Controllers\API\CategoryAPIController::class);
Route::resource('categories', CategoryAPIController::class);

Route::resource('tags', TagAPIController::class);

Route::resource('statuses', StatusAPIController::class);

Route::resource('articles',ArticleAPIController::class);