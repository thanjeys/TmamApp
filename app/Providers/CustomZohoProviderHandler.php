<?php

namespace App\Providers;

use SocialiteProviders\Manager\SocialiteWasCalled;

class CustomZohoProviderHandler
{
    /**
     * Register the provider.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('zoho', CustomZohoProvider::class);
    }
}
