<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserToken;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class ZohoAuthController extends Controller
{
    public function redirectToZoho(): RedirectResponse
    {
        return Socialite::driver('zoho')->redirect();
    }

    public function handleZohoCallback(): RedirectResponse
    {

        try {

            $zohoUser = Socialite::driver('zoho')->user();

            $user = User::firstOrCreate(
                ['provider_id' => $zohoUser->id, 'auth_provider' => 'zoho'],
                [
                    'name' => $zohoUser->name,
                    'email' => $zohoUser->email,
                ]
            );

            $this->updateUserTokens($user->id, 'zoho', $zohoUser);

            Auth::login($user);

            return redirect('/dashboard');
        } catch (Exception $e) {

            Log::info('Zoho authentication failed'.$e->getMessage());

            return redirect('/login')->with('error', 'Zoho authentication failed');
        }
    }

    protected function updateUserTokens($userId, $provider, $zohoUser)
    {
        UserToken::updateOrCreate(
            ['user_id' => $userId, 'provider' => $provider],
            [
                'access_token' => $zohoUser->token,
                'refresh_token' => $zohoUser->refreshToken,
                'expires_at' => now()->addSeconds($zohoUser->expiresIn),
            ]
        );
    }
}
