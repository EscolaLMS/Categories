<?php

use EscolaLms\Categories\Http\Controllers\CategoryAPIController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/categories', 'middleware' => [\Illuminate\Routing\Middleware\SubstituteBindings::class]], function () {
    Route::get('/', [CategoryAPIController::class, 'index']);
    Route::get('tree', [CategoryAPIController::class, 'tree']);
    Route::get('{category}', [CategoryAPIController::class, 'show']);
    Route::group(['middleware' => ['auth:api']], function () {
        Route::post('/', [CategoryAPIController::class, 'create']);
        Route::delete('{category}', [CategoryAPIController::class, 'delete']);
        Route::put('{category}', [CategoryAPIController::class, 'update']);
    });
});
