<?php

namespace App\Jobs;

use App\Models\SyncLog;
use App\Services\ContactService;
use App\Services\SyncLogService;
use App\Services\ZohoService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncContactJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $tries = 3; // Maximum retry attempts

	public $timeout = 120; // Timeout in seconds

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

	/**
	 * Execute the job.
	 */
	public function handle(ZohoService $zohoService, ContactService $contactService, SyncLogService $syncLogService): void
	{
		try {

			if (! $this->accessToken) {
				$syncLogService->handleSyncFailure('Access Token Expired', $this->logId);

				return;
			}

			$lastContactModifiedTimeTable = $contactService->getLastModifiedTime();

			$contacts = $zohoService->fetchContacts($this->page, $this->accessToken, $this->organizationId);
			if (empty($contacts)) {
				// Log::info('no contact' . json_encode($contacts));
				$syncLogService->updateSyncLogCompleted($this->logId, 'completed');

				return;
			}

			// Update or Insert based lastModified Time
			$lastModifiedTimeAPI = end($contacts)['last_modified_time'];
			if ($lastModifiedTimeAPI >= $lastContactModifiedTimeTable) {

				DB::transaction(function () use ($contacts, $contactService, $syncLogService) {
					$contactService->upsertContacts($contacts, $this->organizationId);
					$syncLogService->updateLogRecords($this->logId, count($contacts));
				});
			}

			SyncContactJob::dispatch($this->page + 1, $this->logId, $this->accessToken, $this->organizationId);
		} catch (Exception $e) {
			// Handle failure and update status to 'failed'
			Log::error('SyncContactJob failed', [
				'page' => $this->page,
				'organizationId' => $this->organizationId,
				'error' => $e->getMessage(),
			]);
			$syncLogService->handleSyncFailure($e->getMessage(), $this->logId);
		}
	}

	// private function handleSyncFailure(Exception $e): void
	// {
	// 	SyncLog::find($this->logId)->update([
	// 		'status' => 'failed',
	// 		'error_message' => $e->getMessage(),
	// 		'completed_at' => now()
	// 	]);
	// }
}
