<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
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
            'currency_id' => $this->currency_id,
            'currency_code' => $this->currency_code,
            'currency_name' => $this->currency_name,
            'currency_symbol' => $this->currency_symbol,
            'currency_format' => $this->currency_format,
        ];
    }
}
