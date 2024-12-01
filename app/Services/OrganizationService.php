<?php

namespace App\Services;

use App\Http\Helpers\DateHelper;
use App\Models\Organization;
use Exception;
use Illuminate\Support\Facades\Log;

class OrganizationService
{
	public function updateCreateOrganizations(array $organizations): bool
	{
		try {
			foreach ($organizations as $org) {
				Organization::updateOrCreate(
					['organization_id' => $org['organization_id']],
					[
						'user_id' => auth()->id(),
						'name' => $org['name'],
						'contact_name' => $org['contact_name'] ?? null,
						'email' => $org['email'] ?? null,
						'is_default_org' => $org['is_default_org'],
						'language_code' => $org['language_code'] ?? null,
						'fiscal_year_start_month' => $org['fiscal_year_start_month'],
						'account_created_date' => $org['account_created_date'],
						'time_zone' => $org['time_zone'] ?? null,
						'currency_id' => $org['currency_id'],
						'currency_code' => $org['currency_code'],
						'currency_symbol' => $org['currency_symbol'],
					]
				);
			}

			return true;
		} catch (Exception $e) {
			// Log::error('Error syncing chart of accounts: ' . $e->getMessage());
			throw new ('Failed to upsert chart of accounts: ' . $e->getMessage());
		}
	}

	public function getLastSyncedTime(): ?string
	{
		$last_synced_time = Organization::latest('updated_at')->value('updated_at');

		return DateHelper::formatDateReadable($last_synced_time);
	}
}
