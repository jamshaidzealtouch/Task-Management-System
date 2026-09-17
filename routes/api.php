<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');


Route::middleware('auth:sanctum')
    ->name('api.')
    ->group(function () {

        Route::apiResource('organizations', OrganizationController::class);

        Route::apiResource('projects', ProjectController::class);

        Route::apiResource('tasks', TaskController::class);

        Route::patch(
            '/tasks/{task}/status',
            [TaskController::class, 'updateStatus']
        );
    });


    // Route::middleware('auth:sanctum')->group(function () {

    //     Route::apiResource('organizations', OrganizationController::class);
    
    //     Route::apiResource('projects', ProjectController::class);
    
    //     Route::apiResource('tasks', TaskController::class);
    
    //     Route::patch(
    //         '/tasks/{task}/status',
    //         [TaskController::class, 'updateStatus']
    //     );
    // });