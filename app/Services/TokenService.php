<?php

namespace App\Services;

use App\Models\UserToken;
use Illuminate\Support\Facades\Http;

class TokenService
{
    public function getToken(string $provider): ?string
    {
        $userToken = UserToken::where('user_id', auth()->id())
            ->where('provider', $provider)
            ->first();

        // Check if the token exists and has expired
        if ($userToken && $userToken->expires_at < now()) {
            $userToken = $this->refreshToken($userToken);
        }

        return $userToken?->access_token;
    }

    protected function refreshToken(UserToken $userToken): ?UserToken
    {
        if (! $userToken->refresh_token) {
            return null;
        }

        $response = Http::asForm()->post('https://accounts.zoho.com/oauth/v2/token', [
            'refresh_token' => $userToken->refresh_token,
            'client_id' => config('services.zoho.client_id'),
            'client_secret' => config('services.zoho.client_secret'),
            'grant_type' => 'refresh_token',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $userToken->update([
                'access_token' => $data['access_token'],
                'expires_at' => now()->addSeconds($data['expires_in']),
            ]);

            return $userToken;
        }

        return null;
    }
}
