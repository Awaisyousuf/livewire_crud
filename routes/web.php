<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Posts\PostManager;
use App\Livewire\Posts\PostCreate;
use App\Livewire\Posts\PostEdit;
use App\Http\Controllers\BlogController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/posts', PostManager::class)->name('posts.index');
Route::get('/posts/create', PostCreate::class)->name('posts.create');
Route::get('/posts/{post}/edit', PostEdit::class)->name('posts.edit');

Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/create', [BlogController::class, 'create']);
Route::get('/blogs/{id}/edit', [BlogController::class, 'edit']);