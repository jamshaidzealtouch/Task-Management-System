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
use App\Http\Controllers\ReportController;

// Public Routes
Route::get('/', [LoginController::class, 'index']);

Route::get('/login', [LoginController::class, 'index'])
    ->name('login');

Route::post('/login', [LoginController::class, 'check'])
    ->name('login.check');

Route::get('/register', [RegisterController::class, 'index'])
    ->name('register');

Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.store');

// Invitation Routes
Route::get('/invitations/{token}/accept', [InvitationController::class, 'accept'])
    ->name('invitations.accept');

Route::get('/invitations/register/{token}', [InvitationController::class, 'showRegister'])
    ->name('invitations.register');

Route::post('/invitations/register/{token}', [InvitationController::class, 'completeRegistration'])
    ->name('invitations.register.complete');

// Authenticated Routes
Route::middleware('auth.custom')->group(function () {

    // Authentication
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    // Dashboard
    Route::get('/home', [HomeController::class, 'home'])
        ->name('home');

    Route::get('/about', [HomeController::class, 'about'])
        ->name('about');

        Route::get(
            '/reports/tasks/export',
            [ReportController::class, 'exportTasks']
        )
            ->middleware('auth.custom')
            ->name('reports.tasks.export');

    // Organizations
    Route::resource('organizations', OrganizationController::class);

    // Organization Projects
    Route::get('organizations/{organization}/projects', [ProjectController::class, 'organizationProjects'])
        ->name('organizations.projects.index');

    Route::get('organizations/{organization}/projects/create', [ProjectController::class, 'organizationCreate'])
        ->name('organizations.projects.create');

    Route::post('organizations/{organization}/projects', [ProjectController::class, 'organizationStore'])
        ->name('organizations.projects.store');

    // Projects
    Route::resource('projects', ProjectController::class);

    // Project Users / Members
    Route::get('projects/{project}/users', [ProjectUserController::class, 'index'])
        ->name('projects.users.index');

    Route::get('projects/{project}/users/create', [ProjectUserController::class, 'create'])
        ->name('projects.users.create');

    Route::post('projects/{project}/users', [ProjectUserController::class, 'store'])
        ->name('projects.users.store');

    Route::delete('projects/{project}/users/{user}', [ProjectUserController::class, 'destroy'])
        ->name('projects.users.destroy');

    // Project Invitations
    Route::get('projects/{project}/invitations/create', [InvitationController::class, 'create'])
        ->name('projects.invitations.create');

    Route::post('projects/{project}/invitations', [InvitationController::class, 'store'])
        ->name('projects.invitations.store');

    // Project Tasks
    Route::resource('projects.tasks', TaskController::class)->scoped();

    // Employee My Tasks
    Route::get('/my-tasks', [TaskController::class, 'myTasks'])
        ->name('my.tasks');

    Route::patch('/my-tasks/{task}/status', [TaskController::class, 'updateMyTaskStatus'])
        ->name('my.tasks.status');

    // Task Trash
    Route::get('projects/{project}/tasks/trash', [TaskController::class, 'trash'])
        ->name('projects.tasks.trash');

        Route::post(
            '/tasks/{task}/accept',
            [TaskController::class, 'acceptTask']
        )->name('tasks.accept');
        
        Route::post(
            '/tasks/{task}/reject',
            [TaskController::class, 'rejectTask']
        )->name('tasks.reject');

    // Restore Task
    Route::patch('projects/{project}/tasks/{task}/restore', [TaskController::class, 'restore'])
        ->name('projects.tasks.restore');
});