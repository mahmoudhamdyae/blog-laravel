<?php

namespace App\Providers;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Relations\Relation;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \App\Repositories\PostRepositoryInterface::class,
            \App\Repositories\EloquentPostRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
        Paginator::useBootstrapFive();

        Relation::morphMap([
        'post' => \App\Models\Post::class,
        'comment' => \App\Models\Comment::class,
        'user' => \App\Models\User::class,
    ]);
    }
}
