<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'contact_id' => $this->contact_id,
            'contact_name' => $this->contact_name,
            'company_name' => $this->company_name,
            'contact_type' => ucfirst($this->contact_type),
            'status' => ucfirst($this->status),
            'created_time' => $this->formatDate($this->created_time),
            'last_modified_time' => $this->formatDate($this->last_modified_time),
        ];
    }

    private function formatDate($date): ?string
    {
        return $date ? Carbon::parse($date)->format('d-m-Y H:i') : null;
    }
}
