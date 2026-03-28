<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a user cannot create more than 3 posts', function () {
    $user = User::factory()->create();

    // Create 3 posts for the user
    Post::factory()->count(3)->create(['user_id' => $user->id]);

    // Try to create a 4th post
    $response = $this->actingAs($user)->post(route('posts.store'), [
        'title' => 'Fourth Post',
        'description' => 'This should fail',
        'post_creator' => $user->id,
    ]);

    $response->assertSessionHasErrors(['post_creator']);
    expect(Post::where('user_id', $user->id)->count())->toBe(3);
});

test('a user can create up to 3 posts', function () {
    $user = User::factory()->create();

    // Create 2 posts
    Post::factory()->count(2)->create(['user_id' => $user->id]);

    // Create the 3rd post via request
    $response = $this->actingAs($user)->post(route('posts.store'), [
        'title' => 'Third Post',
        'description' => 'This should pass',
        'post_creator' => $user->id,
    ]);

    $response->assertRedirect(route('posts.index'));
    expect(Post::where('user_id', $user->id)->count())->toBe(3);
});

test('a user can update their posts even if they have 3 posts', function () {
    $user = User::factory()->create();
    $posts = Post::factory()->count(3)->create(['user_id' => $user->id]);
    $postToUpdate = $posts->first();

    $response = $this->actingAs($user)->put(route('posts.update', $postToUpdate->id), [
        'id' => $postToUpdate->id,
        'title' => 'Updated Title',
        'description' => 'Updated Description',
        'post_creator' => $user->id,
    ]);

    $response->assertRedirect(route('posts.show', $postToUpdate->id));
    expect($postToUpdate->fresh()->title)->toBe('Updated Title');
});

test('changing a post owner fails if the new owner already has 3 posts', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    // User 2 has 3 posts
    Post::factory()->count(3)->create(['user_id' => $user2->id]);

    // User 1 has 1 post
    $post = Post::factory()->create(['user_id' => $user1->id]);

    // Try to change owner of User 1's post to User 2
    $response = $this->actingAs($user1)->put(route('posts.update', $post->id), [
        'id' => $post->id,
        'title' => $post->title,
        'description' => $post->description,
        'post_creator' => $user2->id,
    ]);

    $response->assertSessionHasErrors(['post_creator']);
    expect($post->fresh()->user_id)->toBe($user1->id);
});
