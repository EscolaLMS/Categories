<?php

use EscolaLms\Categories\Http\Controllers\CategoryAPIController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/categories'], function () {
    Route::get('/', [CategoryAPIController::class, 'index']);
    Route::get('tree', [CategoryAPIController::class, 'tree']);
    Route::get('{category}', [CategoryAPIController::class, 'show']);

    Route::middleware('auth:api')->group(function () {
        Route::post('/', [CategoryAPIController::class, 'create']);
        Route::delete('{id}', [CategoryAPIController::class, 'destroy']);
        Route::put('{category}', [CategoryAPIController::class, 'update']);
    });
});
