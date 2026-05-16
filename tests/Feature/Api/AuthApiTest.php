<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can register a new user', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('user.email', 'test@example.com')
        ->assertJsonStructure(['token']);

    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);

    $token = $response->json('token');
    $this->withToken($token)->getJson('/api/me')->assertStatus(200);
});

it('can login with valid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt($password = 'i-love-laravel'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => $password,
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('user.email', $user->email)
        ->assertJsonStructure(['token']);

    $token = $response->json('token');
    $this->withToken($token)->getJson('/api/me')->assertStatus(200);
});

it('cannot login with invalid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('correct-password'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('email');

    $this->assertGuest();
});

it('can get the authenticated user info', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test_token')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/me');

    $response->assertStatus(200)
        ->assertJsonPath('data.email', $user->email);
});

it('can logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test_token')->plainTextToken;

    $response = $this->withToken($token)->postJson('/api/logout');

    $response->assertStatus(200);
    expect($user->tokens()->count())->toBe(0);
});
