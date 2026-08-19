```php
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProjectUserController;
use App\Http\Controllers\InvitationController;


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


// Normal Registration
Route::get('/register', [RegisterController::class, 'index'])
    ->name('register');

Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.store');


// ---------------------------------------------------------
// Invitation Routes - PUBLIC
// ---------------------------------------------------------

// Open invitation link
Route::get(
    '/invitations/accept/{token}',
    [InvitationController::class, 'accept']
)->name('invitations.accept');


// Show invitation registration form
Route::get(
    '/invitations/register/{token}',
    [InvitationController::class, 'showRegister']
)->name('invitations.register');


// Submit invitation registration form
Route::post(
    '/invitations/register/{token}',
    [InvitationController::class, 'completeRegistration']
)->name('invitations.register.complete');



/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth.custom')->group(function () {

    // ---------------------------------------------------------
    // Logout
    // ---------------------------------------------------------

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');


    // ---------------------------------------------------------
    // Home
    // ---------------------------------------------------------

    Route::get('/home', [HomeController::class, 'home'])
        ->name('home');


    // ---------------------------------------------------------
    // About
    // ---------------------------------------------------------

    Route::get('/about', [HomeController::class, 'about'])
        ->name('about');


    // ---------------------------------------------------------
    // Organizations
    // ---------------------------------------------------------

    Route::resource(
        'organizations',
        OrganizationController::class
    );


    // ---------------------------------------------------------
    // Organization Projects
    // ---------------------------------------------------------

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


    // ---------------------------------------------------------
    // Projects
    // ---------------------------------------------------------

    Route::resource(
        'projects',
        ProjectController::class
    );


    // ---------------------------------------------------------
    // Project Users
    // ---------------------------------------------------------

    Route::get(
        'projects/{project}/users',
        [ProjectUserController::class, 'index']
    )->name('projects.users.index');

    Route::get(
        'projects/{project}/users/create',
        [ProjectUserController::class, 'create']
    )->name('projects.users.create');

    Route::post(
        'projects/{project}/users',
        [ProjectUserController::class, 'store']
    )->name('projects.users.store');

    Route::delete(
        'projects/{project}/users/{user}',
        [ProjectUserController::class, 'destroy']
    )->name('projects.users.destroy');


    // ---------------------------------------------------------
    // Tasks
    // ---------------------------------------------------------

    Route::resource(
        'projects.tasks',
        TaskController::class
    )->scoped();


    // ---------------------------------------------------------
    // My Tasks
    // ---------------------------------------------------------

    Route::get(
        '/my-tasks',
        [TaskController::class, 'myTasks']
    )->name('my.tasks');


    // ---------------------------------------------------------
    // Employee Update Task Status
    // ---------------------------------------------------------

    Route::patch(
        '/my-tasks/{task}/status',
        [TaskController::class, 'updateMyTaskStatus']
    )->name('my.tasks.status');


    // ---------------------------------------------------------
    // Deleted Tasks
    // ---------------------------------------------------------

    Route::get(
        'projects/{project}/tasks/trash',
        [TaskController::class, 'trash']
    )->name('projects.tasks.trash');


    // ---------------------------------------------------------
    // Restore Task
    // ---------------------------------------------------------

    Route::patch(
        'projects/{project}/tasks/{task}/restore',
        [TaskController::class, 'restore']
    )->name('projects.tasks.restore');


    // ---------------------------------------------------------
    // Invitations - ADMIN / MANAGER ONLY
    // ---------------------------------------------------------

    Route::get(
        '/invitations',
        [InvitationController::class, 'index']
    )->name('invitations.index');

    Route::get(
        '/invitations/create',
        [InvitationController::class, 'create']
    )->name('invitations.create');

    Route::post(
        '/invitations',
        [InvitationController::class, 'store']
    )->name('invitations.store');

});
