<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a post can be created with tags', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('posts.store'), [
        'title' => 'Post with Tags',
        'description' => 'Some description',
        'post_creator' => $user->id,
        'tags' => 'laravel, coding, web',
    ]);

    $post = Post::first();
    expect($post->tags)->toHaveCount(3);
    expect($post->tags->pluck('name'))->toContain('laravel', 'coding', 'web');
});

test('a post tags can be updated', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    $post->attachTags(['old', 'tags']);

    $response = $this->actingAs($user)->put(route('posts.update', $post->id), [
        'id' => $post->id,
        'title' => $post->title,
        'description' => $post->description,
        'post_creator' => $user->id,
        'tags' => 'new, tags, updated',
    ]);

    $post->refresh();
    expect($post->tags)->toHaveCount(3);
    expect($post->tags->pluck('name'))->toContain('new', 'tags', 'updated');
    expect($post->tags->pluck('name'))->not->toContain('old');
});
