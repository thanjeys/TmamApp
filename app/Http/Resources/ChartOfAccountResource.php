<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChartOfAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'account_id' => $this->account_id,
            'account_name' => $this->account_name,
            'account_type' => $this->account_type,
            'is_active' => $this->is_active ? 'Yes' : 'No',
            'created_time' => $this->formatDate($this->created_time),
            'last_modified_time' => $this->formatDate($this->last_modified_time),
        ];
    }

    private function formatDate($date): ?string
    {
        return $date ? Carbon::parse($date)->format('d-m-Y H:i') : null;
    }
}
