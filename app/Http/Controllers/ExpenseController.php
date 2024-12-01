<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddExpenseReceiptRequest;
use App\Http\Resources\ExpenseResource;
use App\Jobs\SyncExpenseJob;
use App\Models\Expense;
use App\Services\ExpenseService;
use App\Services\SyncLogService;
use App\Services\TokenService;
use App\Services\ZohoService;
use App\Traits\HandlesSessionLogout;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ExpenseController extends Controller
{
	use HandlesSessionLogout;

	protected $zohoService;

	public function __construct(ZohoService $zohoService)
	{
		$this->zohoService = $zohoService;
	}

	public function index(Request $request, ExpenseService $expenseService): Response
	{
		$query = Expense::query();

		if ($request->has('search')) {
			$query->where(function ($query) use ($request) {
				$query->where('account_name', 'LIKE', '%' . $request->search . '%')
					->orWhere('customer_name', 'LIKE', '%' . $request->search . '%');
			});
		}

		if ($request->has('paid_through_account_name')) {
			$query->where('paid_through_account_name', 'LIKE', $request->paid_through_account_name);
		}
		if ($request->has('account_name')) {
			$query->where('account_name', 'LIKE', $request->account_name);
		}

		$expenses = ExpenseResource::collection($query->paginate(10));

		$formatted_last_synced_time = $expenseService->getLastSyncedTime();
		$expenseAccountNames = $expenseService->getExpenseAccountNames();
		$paidAccountNames = $expenseService->getPaidAccountNames();

		return Inertia::render('Expenses/Index', compact('expenses', 'formatted_last_synced_time', 'expenseAccountNames', 'paidAccountNames'));
	}

	public function syncExpenses(SyncLogService $syncLogService, TokenService $tokenService, ZohoService $zohoService)
	{
		try {

			$accessToken = $tokenService->getToken('zoho');

			$organizationId = $zohoService->getOrganizationIdForUser();

			$syncLog = $syncLogService->getInProgress('expenses');

			if (! $syncLog) {

				$syncLog = $syncLogService->create('expenses');

				SyncExpenseJob::dispatch(1, $syncLog->id, $accessToken, $organizationId);

				return back()->with(['message' => 'Expense Sync Job is Initiated. Visit SyncLogs Page for the status.']);
			} else {
				return back()->with(['message' => 'Expense Sync is already in progress. Kindly wait until it completes.']);
			}
		} catch (Exception $e) {
			Log::error('Failed to Sync Expenses' . $e->getMessage());

			return $this->handleTokenExpired($e->getMessage())
				?? back()->with('error', 'syncExpenses synced failed! Try again after some time.');
		}
	}

	public function edit($id): Response
	{
		$expense = new ExpenseResource(Expense::find($id));

		return Inertia::render('Expenses/Edit', compact('expense'));
	}

	public function update(AddExpenseReceiptRequest $request): RedirectResponse
	{
		try {

			$receiptPath = $request->file('receipt')->store('receipts');

			$this->zohoService->attachExpenseReceipt($request->expense_id, storage_path('app/' . $receiptPath));

			return redirect()->route('expenses')->with('message', 'Receipt attached successfully');
		} catch (Exception $e) {

			Log::error('Add Receipt failed: ' . $e->getMessage());

			return $this->handleTokenExpired($e->getMessage())
				?? back()->with('error', 'Failed to attach receipt');
		}
	}
}
