<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GithubProvider;
use Laravel\Socialite\Two\User as SocialiteUser;

uses(RefreshDatabase::class);

it('redirects to github', function () {
    $response = $this->get('/auth/redirect');

    $response->assertRedirect();
    expect($response->getTargetUrl())
        ->toContain('github.com/login/oauth/authorize')
        ->toContain('prompt=select_account');
});

it('can login or register a user with github', function () {
    // Mock the Socialite user returned from GitHub
    $socialiteUser = Mockery::mock(SocialiteUser::class);
    $socialiteUser->id = '12345678';
    $socialiteUser->name = 'GitHub User';
    $socialiteUser->nickname = 'githubuser';
    $socialiteUser->email = 'githubuser@example.com';
    $socialiteUser->token = 'mock-token';
    $socialiteUser->refreshToken = 'mock-refresh-token';

    // Mock the GitHub Provider
    $provider = Mockery::mock(GithubProvider::class);
    $provider->shouldReceive('user')->once()->andReturn($socialiteUser);

    // Mock the Socialite driver call
    Socialite::shouldReceive('driver')->once()->with('github')->andReturn($provider);

    // Call the callback route
    $response = $this->get('/auth/callback?code=mock-code&state=mock-state');

    // Assert redirection and authentication
    $response->assertRedirect('/home');
    $this->assertDatabaseHas('users', [
        'email' => 'githubuser@example.com',
        'github_id' => '12345678',
        'github_token' => 'mock-token',
        'github_refresh_token' => 'mock-refresh-token',
    ]);

    $this->assertAuthenticated();
});

it('can link a github account if a user with the same email already exists', function () {
    // Create an existing user
    $existingUser = User::factory()->create([
        'email' => 'existing@example.com',
        'password' => bcrypt('password'),
    ]);

    // Mock the Socialite user with the same email
    $socialiteUser = Mockery::mock(SocialiteUser::class);
    $socialiteUser->id = '98765432';
    $socialiteUser->name = 'Existing User';
    $socialiteUser->nickname = 'existinguser';
    $socialiteUser->email = 'existing@example.com';
    $socialiteUser->token = 'new-mock-token';
    $socialiteUser->refreshToken = 'new-mock-refresh-token';

    $provider = Mockery::mock(GithubProvider::class);
    $provider->shouldReceive('user')->once()->andReturn($socialiteUser);

    Socialite::shouldReceive('driver')->once()->with('github')->andReturn($provider);

    // Call the callback route
    $response = $this->get('/auth/callback?code=mock-code&state=mock-state');

    // Assert redirections and DB updates
    $response->assertRedirect('/home');

    // Check user was updated, not duplicated
    expect(User::count())->toBe(1);

    $this->assertDatabaseHas('users', [
        'email' => 'existing@example.com',
        'github_id' => '98765432',
        'github_token' => 'new-mock-token',
        'github_refresh_token' => 'new-mock-refresh-token',
    ]);

    $this->assertAuthenticatedAs($existingUser);
});
