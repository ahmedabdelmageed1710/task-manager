<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\TaskController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Route::resource('tasks', TaskController::class); // Register the TaskController for API resource routes

Route::middleware(['auth:api', 'role:manager'])->group(function () {
    Route::get('tasks/{id}', [TaskController::class, 'show'])->name('tasks.show'); // Route to get a specific task
    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store'); // Route for creating a new task
    Route::put('tasks/{id}', [TaskController::class, 'update'])->name('tasks.update'); // Route for updating a task
    Route::get('all-tasks', [TaskController::class, 'allTasks']); // Route to get all tasks
    Route::post('assign-task', [TaskController::class, 'assignTask'])->name('tasks.assign'); // Route for assigning tasks
});

Route::middleware(['auth:api', 'role:employee'])->group(function () {
    Route::get('get-user-task/{id}', [TaskController::class, 'getUserTask']); // Route to list tasks for employees
    Route::get('all-user-tasks', [TaskController::class, 'allTasksByUser'])->name('tasks.allUserTasks'); // Route to get all tasks for the authenticated user
    Route::post('update-user-task-status/{id}', [TaskController::class, 'updateUserTaskStatus'])->name('tasks.updateUserTaskStatus'); // Route to update task status for the authenticated user
});
