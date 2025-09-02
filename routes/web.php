<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

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



Route::middleware(['auth','verified'])->group(function () {
    Route::post('/posts', [PostController::class,'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class,'destroy'])->name('posts.destroy');

    Route::post('/posts/{post}/comments', [CommentController::class,'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class,'destroy'])->name('comments.destroy');

    Route::post('/posts/{post}/like', [LikeController::class,'store'])->name('likes.store');
    Route::delete('/posts/{post}/like', [LikeController::class,'destroy'])->name('likes.destroy');
});


require __DIR__.'/auth.php';

