<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirectToProvider(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Obtain the user information from the provider.
     */
    public function handleProviderCallback(string $provider): RedirectResponse
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors([$provider => "Failed to authenticate with {$provider}."]);
        }

        $idField = "{$provider}_id";
        $tokenField = "{$provider}_token";
        $refreshTokenField = "{$provider}_refresh_token";

        // Find user by provider ID
        $user = User::where($idField, $socialUser->id)->first();

        if (! $user) {
            // Fallback email if OAuth email is private
            $email = $socialUser->email ?? ($socialUser->nickname."@{$provider}.com");
            $user = User::where('email', $email)->first();

            if ($user) {
                // Link account if user already exists with this email
                $user->update([
                    $idField => $socialUser->id,
                    $tokenField => $socialUser->token,
                    $refreshTokenField => $socialUser->refreshToken,
                ]);
            } else {
                // Register a new user
                $user = User::create([
                    'name' => $socialUser->name ?? $socialUser->nickname,
                    'email' => $email,
                    $idField => $socialUser->id,
                    $tokenField => $socialUser->token,
                    $refreshTokenField => $socialUser->refreshToken,
                    // password remains null
                ]);
            }
        } else {
            // Update token
            $user->update([
                $tokenField => $socialUser->token,
                $refreshTokenField => $socialUser->refreshToken,
            ]);
        }

        Auth::login($user);

        return redirect('/home');
    }
}
