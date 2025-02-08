<?php

namespace App\Http\Requests\Site\Terms;

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
            'address_ar' => 'required|unique:site_terms,address->ar',
            'address_en' => 'required|unique:site_terms,address->en',
            'sub_address_ar' => 'required|unique:site_terms,sub_address->ar',
            'sub_address_en' => 'required|unique:site_terms,sub_address->en',
            'details_ar' => 'required',
            'details_en' => 'required',
            'image' => 'sometimes|mimes:jpg,jpeg,png',
        ];
    }
}
