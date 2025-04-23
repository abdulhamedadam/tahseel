<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MasrofatResource extends JsonResource
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
            'value' => $this->value,
            'notes' => $this->notes ?? 'N/A',
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'employee' => $this->employee ? $this->employee?->first_name . ' ' . $this->employee?->last_name : null,
            'sarf_band' => $this->sarf_band ? $this->sarf_band->title : null,
            'created_by' => $this->user ? $this->user->name : null,
        ];
    }
}
