<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can list all posts', function () {
    $user = User::factory()->create();
    Post::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user, 'sanctum')->getJson('/api/posts');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

it('can show a single post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user, 'sanctum')->getJson("/api/posts/{$post->id}");

    $response->assertStatus(200)
        ->assertJsonPath('data.title', $post->title);
});

it('can create a post', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/posts', [
        'title' => 'New API Post',
        'description' => 'Description for API post',
        'post_creator' => $user->id,
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.title', 'New API Post');

    $this->assertDatabaseHas('posts', ['title' => 'New API Post']);
});

it('can update a post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user, 'sanctum')->putJson("/api/posts/{$post->id}", [
        'title' => 'Updated Title',
        'description' => 'Updated description',
        'post_creator' => $user->id,
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.title', 'Updated Title');
});

it('can delete a post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/posts/{$post->id}");

    $response->assertStatus(200);
    $this->assertSoftDeleted('posts', ['id' => $post->id]);
});

it('can add a comment to a post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user, 'sanctum')->postJson("/api/posts/{$post->id}/comments", [
        'body' => 'This is an API comment',
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('comments', ['body' => 'This is an API comment']);
});
