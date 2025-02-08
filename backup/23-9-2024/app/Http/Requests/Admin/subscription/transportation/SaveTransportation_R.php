<?php

namespace App\Http\Requests\Admin\subscription\transportation;

use Illuminate\Foundation\Http\FormRequest;

class SaveTransportation_R extends FormRequest
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
            'moving_day'=>'required',
            'trip_time'=>'required',
            'moving_time'=>'required',
//            'car_type'=>'required',
//            'persons_number'=>'required',
        ];
    }
}
