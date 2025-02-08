<?php

namespace App\Http\Requests\Site\Teacher;

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
            'name_ar' => 'required|unique:site_teachers,name->ar',
            'name_en' => 'required|unique:site_teachers,name->en',
            'phone' => 'required|unique:site_teachers,phone',
            'email' => 'required|unique:site_teachers,email',
            'description_ar' => 'required',
            'description_en' => 'required',
            'jop_title_ar' => 'required',
            'jop_title_en' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png',
        ];
    }
}
