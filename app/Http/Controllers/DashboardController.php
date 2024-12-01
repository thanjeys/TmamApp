<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        // $organizations = OrganizationResource::collection(Organization::all());

        // $last_synced_time = Organization::latest('updated_at')->value('updated_at');

        // $formatted_last_synced_time = $last_synced_time ? Carbon::parse($last_synced_time)->format('d M Y, h:i A') : null;

        return Inertia::render('Dashboard');
    }
}
