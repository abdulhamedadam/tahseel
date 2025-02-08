<?php

namespace App\Http\Requests\Admin\subscription\member_subscriptions;

use Illuminate\Foundation\Http\FormRequest;

class SaveMemberSubscriptions extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'member_id' => 'required',
            'main_start_date' => 'required',
            'end_date' => 'required',
            'main_discount' => 'required',
            'main_subscription_id' => 'required',
            'pay_method' => 'required',
            'transportation' => 'required',

            'kt_docs_repeater_advanced.*.start_date' => 'required',
            'kt_docs_repeater_advanced.*.discount' => 'required',
            'kt_docs_repeater_advanced.*.subscription_id' => 'required',
            'kt_docs_repeater_advanced.*.type' => 'required',


        ];
    }
}
