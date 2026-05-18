<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    ->group(function () {
        Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
        Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
        Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
        Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
        Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    }
    );

// Comments
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy')->middleware('auth');

Route::get('/posts/{post}/json', [PostController::class, 'getPostData'])->name('posts.json')->middleware('auth');

// 301 Moved Permanently
Route::redirect('/here', '/there');
Route::redirect('/here', '/there', 302);
// 302 Found
Route::permanentRedirect('/here1', '/there1');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// API Routes
// Route::prefix('api')->group(base_path('routes/api.php'));

// OAuth Routes
Route::middleware('guest')->group(function () {
    Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('auth.redirect');
    Route::get('/auth/callback/{provider}', [SocialiteController::class, 'handleProviderCallback'])->name('auth.callback');

    // Default routes for backwards compatibility
    Route::get('/auth/redirect', [SocialiteController::class, 'redirectToProvider'])->defaults('provider', 'github');
    Route::get('/auth/callback', [SocialiteController::class, 'handleProviderCallback'])->defaults('provider', 'github');
});
