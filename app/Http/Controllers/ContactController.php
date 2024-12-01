<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactResource;
use App\Jobs\SyncContactJob;
use App\Models\Contact;
use App\Services\ContactService;
use App\Services\SyncLogService;
use App\Services\TokenService;
use App\Services\ZohoService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function index(Request $request, ContactService $contactService): Response
    {
        $query = Contact::query();

        if ($request->has('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('contact_name', 'LIKE', '%'.$request->search.'%')
                    ->orWhere('company_name', 'LIKE', '%'.$request->search.'%');
            });
        }

        $contacts = ContactResource::collection($query->paginate(10));

        $formatted_last_synced_time = $contactService->getLastSyncedTime();

        return Inertia::render('Contact', compact('contacts', 'formatted_last_synced_time'));
    }

    public function syncContacts(SyncLogService $syncLogService, TokenService $tokenService, ZohoService $zohoService): RedirectResponse
    {
        try {

            $accessToken = $tokenService->getToken('zoho');

            $organizationId = $zohoService->getOrganizationIdForUser();

            $syncLog = $syncLogService->getInProgress('contacts');

            if (! $syncLog) {

                $syncLog = $syncLogService->create('contacts');

                SyncContactJob::dispatch(1, $syncLog->id, $accessToken, $organizationId);

                return back()->with(['message' => 'Contact Sync Job is Initiated. Visit SyncLogs Page  for the status.']);
            } else {
                return back()->with(['message' => 'Contact Sync is already in progress. Kindly wait until it completes.']);
            }
        } catch (Exception $e) {
            Log::error('Failed to Sync Contacts'.$e->getMessage());

            return back()->with(['message' => 'syncContacts synced failed! Try again after some time.']);
        }
    }
}
