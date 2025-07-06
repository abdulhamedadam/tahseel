<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'clientType' => $this->client_type,
            'user' => $this->user,
            'boxSwitch' => $this->box_switch,
            'subscriptionDate' => $this->subscription_date,
            'startDate' => $this->start_date,
            'address' => $this->address1,
            'subscriptionName' => $this->subscription ? $this->subscription->name : null,
            // 'subscriptionPrice' => $this->subscription ? $this->subscription->price : null,
            'subscriptionPrice' => (string)number_format($this->invoices->sum('remaining_amount'), 2, '.', ''),
            // 'total_remaining_amount' => $this->invoices->sum('remaining_amount'),
            'currency' => get_app_config_data('currency')
        ];
    }
}
