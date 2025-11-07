<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [TestController::class, 'firstAction']/*function () {
    $localName = 'ahmed';
    $books = ['php', 'java', 'c'];
    return view('test', ['name' => $localName, 'books' => $books]);
}*/);

Route::get('/hello', [TestController::class, 'greet']);

// Posts

Route::get('/posts', [PostController::class, 'index']) -> name('posts.index');
Route::get('/posts/create', [PostController::class, 'create']) -> name('posts.create');
Route::post('/posts', [PostController::class, 'store']) -> name('posts.store');
Route::get('/posts/{post}/edit', [PostController::class, 'edit']) -> name('posts.edit');
Route::put('/posts/{post}', [PostController::class, 'update']) -> name('posts.update');
Route::delete('/posts/{post}', [PostController::class, 'destroy']) -> name('posts.destroy');
Route::get('/posts/{post}', [PostController::class, 'show']) -> name('posts.show');
