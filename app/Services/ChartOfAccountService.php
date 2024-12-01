<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class ChartOfAccountService
{
	// Sync chart of accounts with external data
	public function upsertChartOfAccounts(array $accounts, int $organizationId): void
	{
		try {
			$existingAccounts = $this->getExistingAccounts($accounts, $organizationId);
			$upsertData = $this->prepareUpsertData($accounts, $existingAccounts, $organizationId);

			if (! empty($upsertData)) {
				$this->upsertAccounts($upsertData);
			}
		} catch (Exception $e) {
			// Log the error and rethrow to be handled by the controller
			// Log::error('Error syncing chart of accounts: ' . $e->getMessage());
			throw new Exception('Failed to upsert chart of accounts: ' . $e->getMessage());
		}
	}

	// Retrieve existing accounts for comparison
	private function getExistingAccounts(array $accounts, int $organizationId): Collection
	{
		return ChartOfAccount::whereIn('account_id', array_column($accounts, 'account_id'))
			->where('organization_id', $organizationId)
			->get()
			->keyBy('account_id');
	}

	// Prepare data for upsert operation
	private function prepareUpsertData(array $accounts, $existingAccounts, int $organizationId): array
	{
		$upsertData = [];
		foreach ($accounts as $account) {
			$existingAccount = $existingAccounts->get($account['account_id']);
			if (! $existingAccount || $existingAccount->last_modified_time < $account['last_modified_time']) {
				$upsertData[] = $this->buildAccountData($account, $organizationId);
			}
		}

		return $upsertData;
	}

	// Build individual account data for upsert
	private function buildAccountData(array $account, int $organizationId): array
	{
		return [
			'organization_id' => $organizationId,
			'account_id' => $account['account_id'],
			'account_name' => $account['account_name'],
			'account_code' => $account['account_code'],
			'account_type' => $account['account_type'],
			'is_user_created' => $account['is_user_created'],
			'is_system_account' => $account['is_system_account'],
			'is_standalone_account' => $account['is_standalone_account'],
			'is_active' => $account['is_active'],
			'created_time' => $this->formatDate($account['created_time']),
			'last_modified_time' => $this->formatDate($account['last_modified_time']),
			'updated_at' => now(),
		];
	}

	// Perform the upsert operation in database
	private function upsertAccounts(array $upsertData): void
	{
		ChartOfAccount::upsert(
			$upsertData,
			['account_id', 'organization_id'],
			['account_name', 'account_code', 'account_type', 'is_user_created', 'is_system_account', 'is_standalone_account', 'is_active', 'created_time', 'last_modified_time', 'updated_at']
		);
	}

	private function formatDate($date): ?string
	{
		return $date ? Carbon::parse($date)->toDateTimeString() : null;
	}

	public function getLastSyncedTime(): ?string
	{
		$last_synced_time = ChartOfAccount::latest('updated_at')->value('updated_at');

		return $last_synced_time ? Carbon::parse($last_synced_time)->format('d M Y, h:i A') : null;
	}
}
