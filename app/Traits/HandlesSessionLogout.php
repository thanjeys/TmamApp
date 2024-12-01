<?php

namespace App\Traits;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

trait HandlesSessionLogout
{
    protected function handleTokenExpired(string $message): ?RedirectResponse
    {
        $responseDecoded = json_decode($message, true);

        if (isset($responseDecoded['code']) && $responseDecoded['code'] == 14) {

            Auth::logout();

            return redirect()->route('login')->with('error', 'Session Expired, Login again to Continue!');
        }

        return null;
    }
}
