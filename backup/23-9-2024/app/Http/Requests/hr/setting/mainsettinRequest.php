<?php

namespace App\Http\Requests\hr\setting;

use Illuminate\Foundation\Http\FormRequest;

class mainsettinRequest extends FormRequest
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
            'title_ar' => 'required|unique:hr_main_setting,title->ar,'.$this->id,
            'title_en' => 'required|unique:hr_main_setting,title->en,'.$this->id,
            'type_code' => 'required',
            'status' => 'required',
        ];

    }
}
