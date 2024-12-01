<?php

namespace App\Services;

use App\Models\Contact;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class ContactService
{
    public function upsertContacts(array $contacts, int $organizationId): void
    {
        try {
            $upsertData = $this->prepareUpsertData($contacts, $organizationId);

            if (! empty($upsertData)) {
                $this->storeUpdateContacts($upsertData);
            }
        } catch (Exception $e) {
            // Log::error('Error syncing Contacts: ' . $e->getMessage());
            throw new ('Failed to upsert Sync Contacts: '.$e->getMessage());
        }
    }

    private function prepareUpsertData(array $contacts, int $organizationId): array
    {
        $upsertData = [];

        foreach ($contacts as $contact) {
            $upsertData[] = [
                'contact_id' => $contact['contact_id'],
                'contact_name' => $contact['contact_name'],
                'company_name' => $contact['company_name'] ?? null,
                'contact_type' => $contact['contact_type'] ?? null,
                'status' => $contact['status'] ?? null,
                'payment_terms' => $contact['payment_terms'] ?? null,
                'payment_terms_label' => $contact['payment_terms_label'] ?? null,
                'currency_id' => $contact['currency_id'] ?? null,
                'currency_code' => $contact['currency_code'] ?? null,
                'outstanding_receivable_amount' => $contact['outstanding_receivable_amount'] ?? 0,
                'unused_credits_receivable_amount' => $contact['unused_credits_receivable_amount'] ?? 0,
                'first_name' => $contact['first_name'] ?? null,
                'last_name' => $contact['last_name'] ?? null,
                'email' => $contact['email'] ?? null,
                'phone' => $contact['phone'] ?? null,
                'mobile' => $contact['mobile'] ?? null,
                'created_time' => $this->formatDate($contact['created_time']),
                'last_modified_time' => $this->formatDate($contact['last_modified_time']),
                'organization_id' => $organizationId,
            ];
        }

        return $upsertData;
    }

    private function storeUpdateContacts(array $upsertData): void
    {
        Contact::upsert($upsertData, ['contact_id'], [
            'contact_name',
            'company_name',
            'contact_type',
            'status',
            'payment_terms',
            'payment_terms_label',
            'currency_id',
            'currency_code',
            'outstanding_receivable_amount',
            'unused_credits_receivable_amount',
            'first_name',
            'last_name',
            'email',
            'phone',
            'mobile',
            'created_time',
            'last_modified_time',
            'organization_id',
        ]);
    }

    public function getLastSyncedTime(): ?string
    {
        $last_synced_time = Contact::latest('updated_at')->value('updated_at');

        return $last_synced_time ? Carbon::parse($last_synced_time)->format('d M Y, h:i A') : null;
    }

    public function getLastModifiedTime(): string
    {
        return Contact::max('last_modified_time') ?? '1970-01-01 00:00:00';
    }

    private function formatDate($date): ?string
    {
        return $date ? Carbon::parse($date)->toDateTimeString() : null;
    }
}
