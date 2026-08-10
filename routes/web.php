<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationUserController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Login
Route::get('/', [LoginController::class, 'index']);

Route::get('/login', [LoginController::class, 'index'])
    ->name('login');

Route::post('/login', [LoginController::class, 'check'])
    ->name('login.check');


// Register
Route::get('/register', [RegisterController::class, 'index'])
    ->name('register');

Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.store');


/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth.custom')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');


    /*
    |--------------------------------------------------------------------------
    | Home
    |--------------------------------------------------------------------------
    */

    Route::get('/home', [HomeController::class, 'home'])
        ->name('home');


    /*
    |--------------------------------------------------------------------------
    | About
    |--------------------------------------------------------------------------
    */

    Route::get('/about', [HomeController::class, 'about'])
        ->name('about');


    /*
    |--------------------------------------------------------------------------
    | Organizations
    |--------------------------------------------------------------------------
    */

    Route::resource('organizations', OrganizationController::class);


    /*
    |--------------------------------------------------------------------------
    | Organization Users
    |--------------------------------------------------------------------------
    */

    Route::get(
        'organizations/{organization}/users',
        [OrganizationUserController::class, 'index']
    )->name('organizations.users.index');

    Route::get(
        'organizations/{organization}/users/create',
        [OrganizationUserController::class, 'create']
    )->name('organizations.users.create');

    Route::post(
        'organizations/{organization}/users',
        [OrganizationUserController::class, 'store']
    )->name('organizations.users.store');


    /*
    |--------------------------------------------------------------------------
    | Organization Projects
    |--------------------------------------------------------------------------
    |
    | These routes are for viewing projects belonging to a specific
    | organization.
    |
    */

    Route::get(
        'organizations/{organization}/projects',
        [ProjectController::class, 'organizationProjects']
    )->name('organizations.projects.index');

    Route::get(
        'organizations/{organization}/projects/create',
        [ProjectController::class, 'organizationCreate']
    )->name('organizations.projects.create');

    Route::post(
        'organizations/{organization}/projects',
        [ProjectController::class, 'organizationStore']
    )->name('organizations.projects.store');


    /*
    |--------------------------------------------------------------------------
    | Projects
    |--------------------------------------------------------------------------
    */

    Route::resource('projects', ProjectController::class);


    /*
    |--------------------------------------------------------------------------
    | Tasks
    |--------------------------------------------------------------------------
    */

    Route::resource('projects.tasks', TaskController::class)
        ->scoped();


    /*
    |--------------------------------------------------------------------------
    | Deleted Tasks
    |--------------------------------------------------------------------------
    */

    Route::get(
        'projects/{project}/tasks/trash',
        [TaskController::class, 'trash']
    )->name('projects.tasks.trash');


    /*
    |--------------------------------------------------------------------------
    | Restore Task
    |--------------------------------------------------------------------------
    */

    Route::patch(
        'projects/{project}/tasks/{task}/restore',
        [TaskController::class, 'restore']
    )->name('projects.tasks.restore');

});