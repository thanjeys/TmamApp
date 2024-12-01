<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use App\Services\CurrencyService;
use App\Services\ZohoService;
use App\Traits\HandlesSessionLogout;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CurrencyController extends Controller
{
    use HandlesSessionLogout;

    protected $zohoService;

    protected $currencyService;

    public function __construct(ZohoService $zohoService, CurrencyService $currencyService)
    {
        $this->zohoService = $zohoService;
        $this->currencyService = $currencyService;
    }

    public function index(Request $request): Response
    {
        $query = Currency::query();

        if ($request->has('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('currency_name', 'LIKE', '%'.$request->search.'%')
                    ->orWhere('currency_code', 'LIKE', '%'.$request->search.'%');
            });
        }

        $currencies = CurrencyResource::collection($query->paginate(5));

        $formatted_last_synced_time = $this->currencyService->getLastSyncedTime();

        return Inertia::render('Currency', compact('currencies', 'formatted_last_synced_time'));
    }

    public function syncCurrencies(): RedirectResponse
    {
        try {

            $currencies = $this->zohoService->fetchCurrencies();

            $organizationId = $this->zohoService->getOrganizationIdForUser();

            $this->currencyService->upsertCurrencies($currencies, $organizationId);

            return back()->with('message', 'Currencies synced successfully!');
        } catch (Exception $e) {

            Log::error('Currency sync failed: '.$e->getMessage());

            return $this->handleTokenExpired($e->getMessage())
                ?? back()->with('error', 'Currencies sync failed, Try again after Some time!');
        }
    }
}
