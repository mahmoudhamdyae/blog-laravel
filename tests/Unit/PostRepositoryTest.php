<?php

use App\Models\Post;
use App\Models\User;
use App\Repositories\EloquentPostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('it deletes posts older than a year', function () {
    $repository = new EloquentPostRepository();
    $user = User::factory()->create();

    // Create a post from 2 years ago
    Post::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subYears(2),
    ]);

    // Create a post from 6 months ago
    Post::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subMonths(6),
    ]);

    $deletedCount = $repository->deletePostsOlderThan(1);

    expect($deletedCount)->toBe(1);
    expect(Post::count())->toBe(1);
    expect(Post::withTrashed()->count())->toBe(2); // Since it uses SoftDeletes
});

test('it does not delete recent posts', function () {
    $repository = new EloquentPostRepository();
    $user = User::factory()->create();

    Post::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subMonths(11),
    ]);

    $deletedCount = $repository->deletePostsOlderThan(1);

    expect($deletedCount)->toBe(0);
    expect(Post::count())->toBe(1);
});
