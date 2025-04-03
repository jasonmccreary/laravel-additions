<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('posts', [\Workbench\App\Http\Controllers\PostController::class, 'index'])->name('posts.index');
Route::get('posts/ability', [\Workbench\App\Http\Controllers\PostController::class, 'ability'])->name('posts.ability');
Route::get('posts/user', [\Workbench\App\Http\Controllers\PostController::class, 'user'])->name('posts.user');
Route::get('posts/modelless', [\Workbench\App\Http\Controllers\PostController::class, 'modelless'])->name('posts.modelless');
Route::get('posts/model/{post}', [\Workbench\App\Http\Controllers\PostController::class, 'show'])->name('posts.model');
Route::get('posts/arguments/{post}', [\Workbench\App\Http\Controllers\PostController::class, 'arguments'])->name('posts.arguments');
Route::get('posts/other/{user}', [\Workbench\App\Http\Controllers\PostController::class, 'other'])->name('posts.other');
Route::get('posts/explicit', [\Workbench\App\Http\Controllers\PostController::class, 'explicit'])->name('posts.explicit');
Route::get('posts/no-fallback', [\Workbench\App\Http\Controllers\PostController::class, 'noFallback'])->name('posts.no-fallback');
