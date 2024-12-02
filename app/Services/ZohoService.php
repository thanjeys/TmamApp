<?php

namespace App\Services;

use App\Models\Organization;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZohoService
{
    protected $baseUrl;

    protected $tokenService;

    protected $organizationId;

    protected $provider = 'zoho';

    public function __construct(TokenService $tokenService)
    {
        $this->baseUrl = env('ZOHO_API_ENDPOINT');
        $this->tokenService = $tokenService;
        $this->organizationId = $this->getOrganizationIdForUser();
    }

    public function fetchOrganizations(): array
    {
        $accessToken = $this->tokenService->getToken($this->provider);

        $url = $this->baseUrl.'organizations';
        $response = Http::withToken($accessToken)->get($url);
        if ($response->successful()) {
            return $response->json()['organizations'];
        }

        throw new \Exception($response->body());
    }

    public function fetchCurrencies(): array
    {
        $accessToken = $this->tokenService->getToken($this->provider);

        $url = $this->baseUrl.'settings/currencies?organization_id='.$this->organizationId;
        $response = Http::withToken($accessToken)->get($url);
        if ($response->successful()) {
            return $response->json()['currencies'];
        }

        throw new \Exception($response->body());
    }

    public function fetchChartOfAccounts(): array
    {
        $accessToken = $this->tokenService->getToken($this->provider);

        $url = $this->baseUrl.'chartofaccounts?organization_id='.$this->organizationId;
        $response = Http::withToken($accessToken)->get($url);
        if ($response->successful()) {
            return $response->json()['chartofaccounts'];
        }

        throw new \Exception($response->body());
    }

    public function fetchContacts(int $page, string $accessToken, string $organizationId): array
    {
        $url = $this->baseUrl.'/contacts';
        $response = Http::withToken($accessToken)
            ->get($url, [
                'organization_id' => $organizationId,
                'sort_column' => 'last_modified_time',
                'sort_order' => 'A',
                'per_page' => 200,
                'page' => $page,
            ]);

        if ($response->successful()) {
            return $response->json()['contacts'];
        }

        throw new \Exception($response->body());
    }

    public function fetchExpenses(int $page, string $accessToken, string $organizationId): array
    {
        $url = $this->baseUrl.'/expenses';
        $response = Http::withToken($accessToken)
            ->get($url, [
                'organization_id' => $organizationId,
                'sort_column' => 'created_time',
                'sort_order' => 'A',
                'per_page' => 100,
                'page' => $page,
            ]);

        if ($response->successful()) {
            return $response->json()['expenses'];
        }

        throw new \Exception($response->body());
    }

    public function getOrganizationIdForUser(): ?string
    {
        $organization = Organization::where('user_id', auth()->id())->where('is_default_org', 1)->first();

        return $organization ? $organization->organization_id : null;
    }

    public function attachExpenseReceipt($expenseId, $receipt)
    {
        $accessToken = $this->tokenService->getToken($this->provider);
        $url = $this->baseUrl.'expenses/'.$expenseId.'/receipt?organization_id='.$this->organizationId;
        Log::info($url);
        $response = Http::withToken($accessToken)
            ->attach('receipt', file_get_contents($receipt), 'receipt.jpg')
            ->post($url, [
                // You can add additional parameters if needed
                // 'account_name' => 'Thananjeyan',
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception($response->body());
    }
}
