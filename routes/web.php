<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

// Login
Route::get('/', [LoginController::class, 'index']);
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'check'])->name('login.check');


Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');


Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');


Route::resource('projects', ProjectController::class);

Route::get(
    'projects/{project}/tasks/trash',
    [TaskController::class, 'trash']
)->name('projects.tasks.trash');

Route::patch(
    'projects/{project}/tasks/{task}/restore',
    [TaskController::class, 'restore']
)->name('projects.tasks.restore');


Route::resource('projects.tasks', TaskController::class);