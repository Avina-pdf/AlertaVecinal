<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\LikeController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\PollController;

Route::prefix('v1')->group(function () {

    // --- Auth pública ---
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login',    [AuthController::class, 'login']);

    // --- Autenticadas ---
    Route::middleware('auth:sanctum')->group(function () {

        // Perfil / token
        Route::get('me',            [AuthController::class, 'me']);
        Route::post('auth/logout',  [AuthController::class, 'logout']);

        // Feed de posts
        Route::get('posts',         [PostController::class, 'index']);
        Route::post('posts',        [PostController::class, 'store']);             // multipart (texto + imágenes)
        Route::get('posts/{post}',  [PostController::class, 'show']);
        Route::delete('posts/{post}', [PostController::class, 'destroy']);         // policy: autor o admin

        // Likes
        Route::post('posts/{post}/like',   [LikeController::class, 'store']);
        Route::delete('posts/{post}/like', [LikeController::class, 'destroy']);

        // Comentarios
        Route::get('posts/{post}/comments',  [CommentController::class, 'index']);
        Route::post('posts/{post}/comments', [CommentController::class, 'store']);
        Route::delete('comments/{comment}',  [CommentController::class, 'destroy']); // policy

        // Reportes (mapa)
        Route::get('reports',  [ReportController::class, 'index']);  // activos
        Route::post('reports', [ReportController::class, 'store']);

        // Encuestas
        Route::get('polls',        [PollController::class, 'index']);
        Route::post('polls',       [PollController::class, 'store']);  // si quieres, restringe a admin (policy / middleware)
        Route::get('polls/{poll}', [PollController::class, 'show']);
        Route::post('polls/{poll}/vote', [PollController::class, 'vote']);
    });
});
