<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TransactionController;

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    //return $request->user();
//});

// Rotas para o administrador
//Route::group(['prefix' => 'admin', 'middleware' => 'auth:api'], function () {
    Route::get('users', [UserController::class,'index']);
    Route::post('users', [UserController::class,'store']);
    Route::get('users/{id}', [UserController::class,'show']);
    Route::get('users/{id}/edit', [UserController::class,'edit']);
    Route::put('users/{id}/edit', [UserController::class,'update']);
    Route::delete('users/{id}/delete', [UserController::class,'destroy']);
//});

// Rotas para o usuário comum
//Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function () {
    Route::get('categories', [CategoryController::class,'index']);
    Route::post('categories', [CategoryController::class,'store']);
    Route::delete('categories/{id}/delete', [CategoryController::class,'destroy']);

    Route::get('transactions', [TransactionController::class,'index']);
    Route::post('transactions', [TransactionController::class,'store']);
    Route::get('transactions/{id}', [TransactionController::class,'show']);
    Route::delete('transactions/{id}/delete', [TransactionController::class,'destroy']);
//});