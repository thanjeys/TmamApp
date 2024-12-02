<?php

namespace App\Providers;

use SocialiteProviders\Zoho\Provider;

class CustomZohoProvider extends Provider
{
    public function withScopes(array $additionalScopes)
    {
        $this->scopes = array_merge($this->scopes, $additionalScopes);
        return $this;
    }

    // Add more customizations if needed
}
