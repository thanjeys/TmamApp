<?php

namespace App\Services;

use App\Models\Currency;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    public function upsertCurrencies(array $currencies, int $organizationId): void
    {
        try {
            $upsertData = $this->prepareUpsertData($currencies, $organizationId);

            if (! empty($upsertData)) {
                $this->storeUpdateCurrencies($upsertData);
            }
        } catch (Exception $e) {
            // Log the error and rethrow to be handled by the controller
            // Log::error('Error syncing chart of accounts: ' . $e->getMessage());
            throw new ('Failed to upsert chart of accounts: '.$e->getMessage());
        }
    }

    private function prepareUpsertData(array $currencies, int $organizationId): array
    {
        $upsertData = [];

        foreach ($currencies as $currency) {
            $upsertData[] = [
                'currency_id' => $currency['currency_id'],
                'organization_id' => $organizationId,
                'currency_code' => $currency['currency_code'],
                'currency_name' => $currency['currency_name'],
                'currency_symbol' => $currency['currency_symbol'],
                'price_precision' => $currency['price_precision'],
                'currency_format' => $currency['currency_format'],
                'is_base_currency' => $currency['is_base_currency'],
                'exchange_rate' => $currency['exchange_rate'],
                'effective_date' => ! empty($currency['effective_date']) ? $currency['effective_date'] : null,
                'updated_at' => now(),
            ];
        }

        return $upsertData;
    }

    private function storeUpdateCurrencies(array $upsertData): void
    {
        Currency::upsert(
            $upsertData,
            ['currency_id', 'organization_id'], // Unique keys for checking if record exists
            ['currency_code', 'currency_name', 'currency_symbol', 'price_precision', 'currency_format', 'is_base_currency', 'exchange_rate', 'effective_date', 'updated_at'] // Columns to update
        );
    }

    public function getLastSyncedTime(): ?string
    {
        $last_synced_time = Currency::latest('updated_at')->value('updated_at');

        return $last_synced_time ? Carbon::parse($last_synced_time)->format('d M Y, h:i A') : null;
    }
}
