<?php

namespace App\Services;

use App\Models\Expense;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class ExpenseService
{
	public function upsertExpenses(array $expenses, int $organizationId): void
	{
		try {
			$upsertData = $this->prepareUpsertData($expenses, $organizationId);

			if (! empty($upsertData)) {
				$this->storeUpdateExpenses($upsertData);
			}
		} catch (Exception $e) {
			// Log::error('Error syncing Expenses: ' . $e->getMessage());
			throw new ('Failed to upsert Sync Expenses: ' . $e->getMessage());
		}
	}

	private function prepareUpsertData(array $expenses, int $organizationId): array
	{
		$upsertData = [];

		foreach ($expenses as $expense) {
			$upsertData[] = [
				'expense_id' => $expense['expense_id'],
				'organization_id' => $organizationId,
				'date' => Carbon::parse($expense['date'])->format('Y-m-d'),
				'account_name' => $expense['account_name'],
				'description' => $expense['description'],
				'currency_id' => $expense['currency_id'],
				'currency_code' => $expense['currency_code'],
				'bcy_total' => $expense['bcy_total'],
				'bcy_total_without_tax' => $expense['bcy_total_without_tax'],
				'total' => $expense['total'],
				'total_without_tax' => $expense['total_without_tax'],
				'is_billable' => $expense['is_billable'],
				'reference_number' => $expense['reference_number'] ?? null,
				'customer_id' => $expense['customer_id'],
				'customer_name' => $expense['customer_name'],
				'status' => $expense['status'],
				'created_time' => $this->formatDate($expense['created_time']),
				'last_modified_time' => $this->formatDate($expense['last_modified_time']),
				'expense_type' => $expense['expense_type'],
				'expense_receipt_name' => $expense['expense_receipt_name'],
				'paid_through_account_name' => $expense['paid_through_account_name'],
				'has_attachment' => $expense['has_attachment'],
			];
		}

		return $upsertData;
	}

	private function storeUpdateExpenses(array $upsertData): bool
	{
		return Expense::upsert($upsertData, ['expense_id'], [
			'organization_id',
			'date',
			'account_name',
			'description',
			'currency_id',
			'currency_code',
			'bcy_total',
			'bcy_total_without_tax',
			'total',
			'total_without_tax',
			'is_billable',
			'reference_number',
			'customer_id',
			'customer_name',
			'status',
			'created_time',
			'last_modified_time',
			'expense_type',
			'expense_receipt_name',
		]);
	}

	public function getLastSyncedTime(): ?string
	{
		$last_synced_time = Expense::latest('updated_at')->value('updated_at');

		return $last_synced_time ? Carbon::parse($last_synced_time)->format('d M Y, h:i A') : null;
	}

	private function formatDate($date): ?string
	{
		return $date ? Carbon::parse($date)->toDateTimeString() : null;
	}

	public function getExpenseAccountNames(): array
	{
		return Expense::select('account_name')
			->distinct()
			->orderBy('account_name')
			->get()
			->pluck('account_name')
			->toArray();
	}

	public function getPaidAccountNames(): array
	{
		return Expense::select('paid_through_account_name')
			->distinct()
			->orderBy('paid_through_account_name')
			->get()
			->pluck('paid_through_account_name')
			->toArray();
	}
}
