<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\api\HomeController;
use App\Http\Controllers\Api\TransactionController;


    Route::get('/home', [HomeController::class,"index"]);
    //Route::put('/home/{id}/edit', [HomeController::class,'edit']);
    Route::put('/api/users/{id}', 'UserController@update');

// Rotas para o administrador
//Route::group(['prefix' => 'admin', 'middleware' => 'auth:api'], function () {
    Route::get('users', [UserController::class,'index']);
    Route::post('users', [UserController::class,'store']);
    Route::get('users/{id}', [UserController::class,'show']);
    Route::get('users/{id}/edit', [UserController::class,'edit']);
    Route::put('users/{id}/edit', [UserController::class,'update']);
    Route::delete('users/{id}/delete', [UserController::class,'destroy']);
//});

// Rotas para o usuário comum ( nao implementado )
//Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function () {
    Route::get('/categories/{user_id}', [CategoryController::class,'index']);
    Route::post('/categories/{user_id}', [CategoryController::class,'store']);
    Route::delete('/categories/{user_id}/delete/{category_id}', [CategoryController::class,'destroy']);

    Route::get('/transactions/{user_id}', [TransactionController::class,'index']);
    Route::post('/transactions/{user_id}', [TransactionController::class,'store']);
    Route::get('transactions', [TransactionController::class, 'list']);
    Route::delete('transactions/{user_id}/delete/{transaction_id}', [TransactionController::class,'destroy']);
//});