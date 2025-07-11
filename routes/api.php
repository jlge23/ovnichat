<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('/productos', ProductoController::class);
Route::get('/productos/{id}', [ProductoController::class, 'show']);

//Route::middleware('auth:sanctum')->get('/productos', [ProductoController::class, 'index']);
