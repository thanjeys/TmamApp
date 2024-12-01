<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'organization_id' => $this->organization_id,
            'name' => $this->name,
            'contact_name' => $this->contact_name,
            'email' => $this->email,
            'is_default_org' => $this->is_default_org,
            'account_created_date' => $this->formatDate($this->account_created_date),
        ];
    }

    private function formatDate($date): ?string
    {
        return $date ? Carbon::parse($date)->format('d-m-Y') : null;
    }
}
