<?php

namespace App\Http\Requests\hr\setting;

use Illuminate\Foundation\Http\FormRequest;

class type_settingRequest extends FormRequest
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
            'name_ar' => 'required|unique:hr_type_setting,title->ar',
            'name_en' => 'required|unique:hr_type_setting,title->en',
            'code' => 'required|unique:hr_type_setting,code',
        ];
    }
}
