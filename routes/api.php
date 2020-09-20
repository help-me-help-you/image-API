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

Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');

Route::apiResource('/products', 'Api\ProductController')->middleware('auth:api');
Route::apiResource('/productsimages', 'Api\ProductImageController')->middleware('auth:api');

/*

alternative method for use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact admin@ionnex.com'
    ], 404);
});
*/
