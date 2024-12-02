<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChartOfAccountResource;
use App\Models\ChartOfAccount;
use App\Services\ChartOfAccountService;
use App\Services\ZohoService;
use App\Traits\HandlesSessionLogout;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ChartOfAccountController extends Controller
{
    use HandlesSessionLogout;

    protected $zohoService;

    protected $chartOfAccountService;

    public function __construct(ZohoService $zohoService, ChartOfAccountService $chartOfAccountService)
    {
        $this->zohoService = $zohoService;
        $this->chartOfAccountService = $chartOfAccountService;
    }

    public function index(Request $request): Response
    {
        $query = ChartOfAccount::query();

        if ($request->has('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('account_name', 'LIKE', '%'.$request->search.'%')
                    ->orWhere('account_type', 'LIKE', '%'.$request->search.'%');
            });
        }

        $accounts = ChartOfAccountResource::collection($query->paginate(10));

        $formatted_last_synced_time = $this->chartOfAccountService->getLastSyncedTime();

        return Inertia::render('ChartOfAccount', compact('accounts', 'formatted_last_synced_time'));
    }

    public function syncChartOfAccounts(): RedirectResponse
    {
        try {

            $accounts = $this->zohoService->fetchChartOfAccounts();

            $organizationId = $this->zohoService->getOrganizationIdForUser();

            $this->chartOfAccountService->upsertChartOfAccounts($accounts, $organizationId);

            return back()->with(['message' => __('CHARTOFACCOUNT_SYNC_SUCCESS')]);
        } catch (Exception $e) {

            Log::error('ChartOfAccount sync failed: '.$e->getMessage());

            return $this->handleTokenExpired($e->getMessage())
                ?? back()->with('error', __('CHARTOFACCOUNT_SYNC_ERROR'));
        }
    }
}
