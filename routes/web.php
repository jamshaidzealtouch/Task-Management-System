<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\OrganizationController;

// Login
Route::get('/', [LoginController::class, 'index']);
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'check'])->name('login.check');

// Register
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Static Pages
Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Organizations
Route::resource('organizations', OrganizationController::class);

// Projects
Route::resource('projects', ProjectController::class);

// Tasks (Nested Resource with Scoped Route Model Binding)
Route::resource('projects.tasks', TaskController::class)
    ->scoped();

// Soft Deleted Tasks
Route::get(
    'projects/{project}/tasks/trash',
    [TaskController::class, 'trash']
)->name('projects.tasks.trash');

// Restore Soft Deleted Task
Route::patch(
    'projects/{project}/tasks/{task}/restore',
    [TaskController::class, 'restore']
)->name('projects.tasks.restore');