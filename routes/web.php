<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome ');
}); 

Route::get('/dashboard', FeedController::class)
    ->middleware(['auth','verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
#############################################################################################################################
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/reportar', [ReportController::class, 'create'])->name('reports.create'); // mapa para seleccionar punto
    Route::post('/reports',   [ReportController::class, 'store'])->name('reports.store'); // guardar
    Route::get('/mapa',       [ReportController::class, 'map'])->name('reports.map');     // ver todos en mapa
});
##############################################################################################################################

Route::middleware(['auth','verified'])->group(function () {
    Route::post('/posts', [PostController::class,'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class,'destroy'])->name('posts.destroy');

    Route::middleware(['auth','verified'])->group(function () {
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}',      [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}',   [PostController::class, 'destroy'])->name('posts.destroy');
});

    Route::post('/posts/{post}/comments', [CommentController::class,'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class,'destroy'])->name('comments.destroy');

    Route::post('/posts/{post}/like', [LikeController::class,'store'])->name('likes.store');
    Route::delete('/posts/{post}/like', [LikeController::class,'destroy'])->name('likes.destroy');
});

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/tag/{tag}', [TagController::class, 'show'])->name('tags.show');
});

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/notificaciones', [NotificationController::class,'index'])->name('notifications.index');
    Route::post('/notificaciones/read-all', [NotificationController::class,'readAll'])->name('notifications.readAll');
    Route::post('/notificaciones/{id}/read', [NotificationController::class,'readOne'])->name('notifications.readOne');

    // endpoint ligero para el badge del navbar
    Route::get('/api/notifications/unread-count', [NotificationController::class,'count'])
        ->name('notifications.count');
});



require __DIR__.'/auth.php';

