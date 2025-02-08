<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;

class MainRequest extends FormRequest
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
            'name_en' => 'required|unique:site_data,name->en,'.$this->id,
            'name_ar' => 'required|unique:site_data,name->ar,'.$this->id,
            'address_en' => 'required|unique:site_data,address->en,'.$this->id,
            'address_ar' => 'required|unique:site_data,address->ar,'.$this->id,
            'description_en' => 'required|unique:site_data,description->en,'.$this->id,
            'description_ar' => 'required|unique:site_data,description->ar,'.$this->id,
            'phone' => 'required',
            'image' => 'sometimes', 'images', 'mimes:jpeg,png,jpg',
            'email' => 'required|email',
        ];
    }
}
