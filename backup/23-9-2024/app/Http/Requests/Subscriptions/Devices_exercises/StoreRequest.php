<?php

namespace App\Http\Requests\Subscriptions\Devices_exercises;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => 'required',
            'link' => 'required',
             'exercise_level' => 'required',
              'device_code' => 'required',
               'groups' => 'required',
               'numbers' => 'required',
        ];
    }
}
