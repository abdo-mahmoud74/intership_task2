<?php

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

// ====== بدون Authentication ======
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// ====== محمية بـ Sanctum ======
Route::middleware('auth:sanctum')->group(function () {

    // user
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // tasks
    Route::get('/tasksAll', [TaskController::class, 'index']);
    Route::get('/tasksOne/{id}', [TaskController::class, 'show']);
    Route::post('/tasksStore', [TaskController::class, 'store']);
    Route::put('/tasksUpdate/{id}', [TaskController::class, 'update']);
    Route::delete('/tasksDelete/{id}', [TaskController::class, 'delete']);

    // projects 
    Route::get('/projectsAll', [ProjectController::class, 'index']);
    Route::get('/projectsOne/{id}', [ProjectController::class, 'show']);
    Route::put('/projectsUpdate/{id}', [ProjectController::class, 'update']);
    Route::delete('/projectsDelete/{id}', [ProjectController::class, 'delete']);
    Route::post('/projectsStore', [ProjectController::class, 'store']);
    // comments
    Route::get('/commentsAll', [App\Http\Controllers\Api\CommentController::class, 'index']);
    Route::post('/commentsStore', [App\Http\Controllers\Api\CommentController::class, 'store']);
    Route::delete('/commentsDelete/{id}', [App\Http\Controllers\Api\CommentController::class, 'delete']);
    // tags
    Route::get('/tagsAll', [TagController::class, 'index']);
    Route::get('/tagsOne/{id}', [TagController::class, 'show']);
    Route::post('/tagsStore', [TagController::class, 'store']);
    Route::delete('/tagsDelete/{id}', [TagController::class, 'delete']);
    Route::put('/tagsUpdate/{id}', [TagController::class, 'update']);

});