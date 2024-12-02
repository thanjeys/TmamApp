<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Services\OrganizationService;
use App\Services\ZohoService;
use App\Traits\HandlesSessionLogout;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class OrganizationController extends Controller
{
    use HandlesSessionLogout;

    protected $zohoService;

    protected $organizationService;

    public function __construct(ZohoService $zohoService, OrganizationService $organizationService)
    {
        $this->zohoService = $zohoService;
        $this->organizationService = $organizationService;
    }

    public function index(): Response
    {
        $organizations = OrganizationResource::collection(Organization::all());

        $formatted_last_synced_time = $this->organizationService->getLastSyncedTime();

        return Inertia::render('Organization', compact('organizations', 'formatted_last_synced_time'));
    }

    public function syncOrganizations(): RedirectResponse
    {
        try {

            $organizations = $this->zohoService->fetchOrganizations();

            $this->organizationService->updateCreateOrganizations($organizations);

            return back()->with('message', __('ORG_SYNC_SUCCESS'));
        } catch (Exception $e) {

            Log::error('Organizations sync failed: '.$e->getMessage());

            return $this->handleTokenExpired($e->getMessage())
                ?? back()->with('error', __('ORG_SYNC_ERROR'));
        }
    }
}
