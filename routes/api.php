<?php

use App\Http\Controllers\GoalsController;
use App\Http\Controllers\UserController;
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

Route::prefix('/goals')->group(function () {

    Route::middleware('auth:sanctum')->get('/', [GoalsController::class, 'index']);

    Route::middleware('auth:sanctum')->post('/store', [GoalsController::class, 'store']);

});

Route::prefix('/login')->group(function () {

    Route::post('/', [UserController::class, 'login']);

    Route::post('/store', [UserController::class, 'store']);

});
