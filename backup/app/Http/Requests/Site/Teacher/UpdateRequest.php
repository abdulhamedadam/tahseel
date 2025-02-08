<?php

namespace App\Http\Requests\Site\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'name_ar' => [
                'required',
                Rule::unique('site_teachers', 'name->ar')->ignore($this->id),
            ],
            'name_en' => [
                'required',
                Rule::unique('site_teachers', 'name->en')->ignore($this->id),
            ],
            'phone' => 'required|unique:site_teachers,phone,'.$this->id,
            'email' => 'required|unique:site_teachers,email,'.$this->id,
            'description_ar' => 'required',
            'description_en' => 'required',
            'jop_title_ar' => 'required',
            'jop_title_en' => 'required',
            'image' => 'sometimes|mimes:jpg,jpeg,png',
        ];
    }
}
