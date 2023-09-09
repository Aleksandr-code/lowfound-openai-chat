<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
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

Route::prefix('/chat')->middleware('auth:sanctum')->group(function(){
    Route::get('/messages', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);
    Route::delete('/messages/{question}', [MessageController::class, 'destroy']);
});

Route::prefix('/auth')->group(function(){
   Route::post('/register', [RegisterController::class, 'register']);
   Route::post('/login', [LoginController::class, 'login']);
   Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
});


