<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\LikeController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\PollController;


Route::prefix('v1')->group(function () {

    Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // ...
    Route::get('posts/count', [PostController::class, 'count']); // 👈
});
    Route::get('ping', fn() => response()->json(['pong' => true]));

    // Público
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/register', [AuthController::class, 'register']);

    // Autenticadas
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);

        Route::get('posts', [PostController::class, 'index']);
        Route::post('posts', [PostController::class, 'store']);
        Route::get('posts/{post}', [PostController::class, 'show']);
        Route::delete('posts/{post}', [PostController::class, 'destroy']);

        Route::post('posts/{post}/like', [LikeController::class, 'store']);
        Route::delete('posts/{post}/like', [LikeController::class, 'destroy']);

        Route::get('posts/{post}/comments', [CommentController::class, 'index']);
        Route::post('posts/{post}/comments', [CommentController::class, 'store']);
        Route::delete('comments/{comment}', [CommentController::class, 'destroy']);

        Route::get('reports', [ReportController::class, 'index']);
        Route::post('reports', [ReportController::class, 'store']);

        Route::get('polls', [PollController::class, 'index']);
        Route::post('polls', [PollController::class, 'store']);
        Route::get('polls/{poll}', [PollController::class, 'show']);
        Route::post('polls/{poll}/vote', [PollController::class, 'vote']);
    });
});
