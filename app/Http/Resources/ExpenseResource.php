<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
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
            'expense_id' => $this->expense_id,
            'account_name' => $this->account_name,
            'currency_code' => $this->currency_code,
            'date' => $this->date,
            'description' => $this->description,
            'customer_name' => $this->customer_name,
            'total' => $this->total,
            'paid_through_account_name' => $this->paid_through_account_name,
            'has_attachment' => $this->has_attachment,
        ];
    }
}
