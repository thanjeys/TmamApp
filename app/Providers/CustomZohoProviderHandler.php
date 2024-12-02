<?php

namespace App\Providers;

use SocialiteProviders\Manager\SocialiteWasCalled;
use App\Providers\CustomZohoProvider;

class CustomZohoProviderHandler
{
    /**
     * Register the provider.
     *
     * @param SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('zoho', CustomZohoProvider::class);
    }
}
