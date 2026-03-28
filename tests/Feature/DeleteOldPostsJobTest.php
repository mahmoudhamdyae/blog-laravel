<?php

use App\Models\Post;
use App\Models\User;
use App\Jobs\DeleteOldPostsJob;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the delete old posts job deletes posts older than a year', function () {
    $user = User::factory()->create();

    // Old post
    Post::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subYears(2),
    ]);

    // New post
    Post::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subMonths(6),
    ]);

    // Dispatch the job
    (new DeleteOldPostsJob())->handle(app(\App\Repositories\PostRepositoryInterface::class));

    expect(Post::count())->toBe(1);
});
