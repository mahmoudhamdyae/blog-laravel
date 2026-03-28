<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;

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

// Route::get('/posts', [PostController::class, 'index']) -> name('posts.index')->middleware(['auth'
// // , 'adminOnly'
// ]);
// Route::get('/posts/create', [PostController::class, 'create']) -> name('posts.create')->middleware('auth');
// Route::get('/posts/{post}', [PostController::class, 'show']) -> name('posts.show')->middleware('auth');
// Route::post('/posts', [PostController::class, 'store']) -> name('posts.store')->middleware('auth');
// Route::get('/posts/{post}/edit', [PostController::class, 'edit']) -> name('posts.edit')->middleware('auth');
// Route::put('/posts/{post}', [PostController::class, 'update']) -> name('posts.update')->middleware('auth');
// Route::delete('/posts/{post}', [PostController::class, 'destroy']) -> name('posts.destroy')->middleware('auth');
Route::middleware(['auth'])
// ->prefix('admin')      // كل الروابط ستبدأ بـ admin/
// ->name('admin.')       // كل أسماء الروابط ستبدأ بـ admin.
->group(function() {
     Route::get('/posts', [PostController::class, 'index']) -> name('posts.index');
     Route::get('/posts/create', [PostController::class, 'create']) -> name('posts.create');
     Route::get('/posts/{post}', [PostController::class, 'show']) -> name('posts.show');
     Route::post('/posts', [PostController::class, 'store']) -> name('posts.store');
     Route::get('/posts/{post}/edit', [PostController::class, 'edit']) -> name('posts.edit');
     Route::put('/posts/{post}', [PostController::class, 'update']) -> name('posts.update');
     Route::delete('/posts/{post}', [PostController::class, 'destroy']) -> name('posts.destroy');
    }
);

// Comments
Route::post('/posts/{post}/comments', [CommentController::class, 'store']) -> name('comments.store')->middleware('auth');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy']) -> name('comments.destroy')->middleware('auth');

Route::get('/posts/{post}/json', [PostController::class, 'getPostData']) -> name('posts.json')->middleware('auth');



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
