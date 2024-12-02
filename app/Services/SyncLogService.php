<?php

namespace App\Services;

use App\Models\SyncLog;

class SyncLogService
{
	public function create(string $jobname): SyncLog
	{
		return SyncLog::create([
			'user_id' => auth()->id(),
			'job_name' => $jobname,
		]);
	}

	public function getInProgress(string $jobname): ?SyncLog
	{
		return SyncLog::where('user_id', auth()->id())
			->where('job_name', $jobname)
			->where('status', 'in-progress')
			->first();
	}

	public function updateSyncLogStatus(int $syncLogId, string $status): bool
	{
		return SyncLog::find($syncLogId)->update(['status' => $status]);
	}

	public function updateLogRecords(int $syncLogId, int $recordsProcessed): bool
	{
		$syncLog = SyncLog::find($syncLogId);
		$syncLog->increment('records_processed', $recordsProcessed);
		return $syncLog->save();
	}

	public function updateSyncLogCompleted(int $logId): bool
	{
		return SyncLog::find($logId)->update([
			'status' => 'completed',
			'completed_at' => now(),
		]);
	}

	public function handleSyncFailure(string $message, int $logId): bool
	{
		return SyncLog::find($logId)->update([
			'status' => 'failed',
			'error_message' => $message,
			'completed_at' => now(),
		]);
	}
}
