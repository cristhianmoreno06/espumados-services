<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
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

Route::post('login', [LoginController::class, 'attemptLogin']);

Route::group(["prefix" => "user", "middleware" => "auth:api"], function () {
    Route::get('list', [UserController::class, 'index']);
    Route::get('list/{id}', [UserController::class, 'show']);
    Route::post('storeOrUpdateUser', [UserController::class, 'storeOrUpdateUser']);
    Route::delete('delete/{id}', [UserController::class, 'destroy']);
});

Route::group(["prefix" => "rol", "middleware" => "auth:api"], function () {
    Route::get('list', [RolController::class, 'index']);
    Route::get('list/{id}', [RolController::class, 'show']);
    Route::post('storeOrUpdateRol', [RolController::class, 'storeOrUpdateRol']);
    Route::delete('delete/{id}', [RolController::class, 'destroy']);
});
