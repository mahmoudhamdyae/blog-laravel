<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('language switcher route sets session and redirects back', function () {
    $response = $this->get(route('lang.switch', 'ar'));

    $response->assertRedirect();
    $this->assertEquals('ar', session()->get('locale'));

    $response = $this->get(route('lang.switch', 'en'));

    $response->assertRedirect();
    $this->assertEquals('en', session()->get('locale'));
});

test('middleware sets locale on web routes from session', function () {
    $this->withSession(['locale' => 'ar']);

    $response = $this->get('/');

    $response->assertStatus(200);
    $this->assertEquals('ar', app()->getLocale());
});

test('middleware sets locale on API routes from Accept-Language header', function () {
    $response = $this->withHeaders([
        'Accept-Language' => 'ar',
    ])->getJson('/api/test-api');

    $response->assertStatus(200);
    $this->assertEquals('ar', app()->getLocale());

    $response = $this->withHeaders([
        'Accept-Language' => 'en',
    ])->getJson('/api/test-api');

    $response->assertStatus(200);
    $this->assertEquals('en', app()->getLocale());
});

test('Post model returns localized attributes based on active locale', function () {
    $user = User::factory()->create();

    $post = Post::create([
        'title' => 'English Title',
        'title_ar' => 'العنوان بالعربي',
        'description' => 'English Description',
        'description_ar' => 'الوصف بالعربي',
        'user_id' => $user->id,
    ]);

    // Test default/English locale
    app()->setLocale('en');
    expect($post->title)->toBe('English Title');
    expect($post->description)->toBe('English Description');

    // Test Arabic locale
    app()->setLocale('ar');
    expect($post->title)->toBe('العنوان بالعربي');
    expect($post->description)->toBe('الوصف بالعربي');
});

test('Post index and show APIs return correct translation based on header', function () {
    $user = User::factory()->create();

    $post = Post::create([
        'title' => 'English Title',
        'title_ar' => 'العنوان بالعربي',
        'description' => 'English Description',
        'description_ar' => 'الوصف بالعربي',
        'user_id' => $user->id,
    ]);

    // Query in Arabic
    $response = $this->actingAs($user, 'sanctum')->withHeaders([
        'Accept-Language' => 'ar',
    ])->getJson('/api/posts');

    $response->assertStatus(200);
    $data = $response->json('data');
    expect($data[0]['title'])->toBe('العنوان بالعربي');
    expect($data[0]['description'])->toBe('الوصف بالعربي');

    // Query in English
    $response = $this->actingAs($user, 'sanctum')->withHeaders([
        'Accept-Language' => 'en',
    ])->getJson('/api/posts');

    $response->assertStatus(200);
    $data = $response->json('data');
    expect($data[0]['title'])->toBe('English Title');
    expect($data[0]['description'])->toBe('English Description');
});
