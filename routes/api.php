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

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);


Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('organizations', OrganizationController::class);

    // Projects
    Route::apiResource('projects', ProjectController::class);

});
// Route::middleware('auth:sanctum')->group(function () {

//     Route::get('/tasks', [TaskController::class, 'index']);

//     Route::post('/tasks', [TaskController::class, 'store']);

//     Route::get('/tasks/{task}', [TaskController::class, 'show']);

//     Route::put('/tasks/{task}', [TaskController::class, 'update']);

//     Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);

//     Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
// });

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('tasks', TaskController::class);

    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
});