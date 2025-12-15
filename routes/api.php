<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\LabelController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//user routes
Route::get('/test', function() {
    return response()->json(['message' => 'hello world']);
});
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/test', function() {
    return response()->json(['message' => 'POST works']);
});
Route::middleware('auth:sanctum')->get('/me', [UserController::class, 'me']);
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);

//task routes
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
    Route::get('/tasks/project/{projectId}', [TaskController::class, 'getByProject']);
    Route::get('/tasks/{id}/labels', [TaskController::class, 'fethchLabels']);
    Route::post('/tasks', [TaskController::class, 'add']);
    Route::post('/tasks/{id}/labels', [TaskController::class, 'addLabel']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{taskId}/labels/{labelId}', [TaskController::class, 'deleteTaskLabel']);
    Route::delete('/tasks/{id}', [TaskController::class, 'delete']);
});

// status routes
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/statuses', [StatusController::class, 'index']);
    Route::get('/statuses/{id}', [StatusController::class, 'show']);
    Route::post('/statuses', [StatusController::class, 'add']);
    Route::put('/statuses/{id}', [StatusController::class, 'update']);
    Route::delete('/statuses/{id}', [StatusController::class, 'delete']);
});

// project routes
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::post('/projects', [ProjectController::class, 'add']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'delete']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/labels', [LabelController::class, 'index']);
    Route::post('/labels', [LabelController::class, 'add']);
    Route::put('/labels/{id}', [LabelController::class, 'update']);
    Route::delete('/labels/{id}', [LabelController::class, 'delete']);
});