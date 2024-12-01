<?php

namespace App\Jobs;

use App\Services\ExpenseService;
use App\Services\SyncLogService;
use App\Services\ZohoService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncExpenseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $page;

    private $logId;

    private $accessToken;

    private $organizationId;

    /**
     * Create a new job instance.
     */
    public function __construct($page, $logId, $accessToken, $organizationId)
    {
        $this->page = $page;
        $this->logId = $logId;
        $this->accessToken = $accessToken;
        $this->organizationId = $organizationId;
    }

    public function handle(ZohoService $zohoService, ExpenseService $expenseService, SyncLogService $syncLogService): void
    {
        try {

            if (! $this->accessToken) {
                $syncLogService->handleSyncFailure('Access Token Expired', $this->logId);

                return;
            }

            $expenses = $zohoService->fetchExpenses($this->page, $this->accessToken, $this->organizationId);

            Log::info('expenses API Result'.json_encode($expenses));
            if (empty($expenses)) {
                $syncLogService->updateSyncLogCompleted($this->logId, 'completed');

                return;
            }

            $expenseService->upsertExpenses($expenses, $this->organizationId);
            $syncLogService->updateLogRecords($this->logId, count($expenses));

            SyncExpenseJob::dispatch($this->page + 1, $this->logId, $this->accessToken, $this->organizationId);
        } catch (Exception $e) {
            // Handle failure and update status to 'failed'
            $syncLogService->handleSyncFailure($e->getMessage(), $this->logId);
        }
    }
}
