<?php

namespace App\Http\Requests\Site\About;

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
            'address_ar' => [
                'required',
                Rule::unique('site_abouts', 'address->ar')->ignore($this->id),
            ],
            'address_en' => [
                'required',
                Rule::unique('site_abouts', 'address->en')->ignore($this->id),
            ],
            'sub_address_ar' => 'required|unique:site_abouts,sub_address->ar,'.$this->id,
            'sub_address_en' => 'required|unique:site_abouts,sub_address->en,'.$this->id,
            'details_ar' => 'required',
            'details_en' => 'required',
            'image' => 'sometimes', 'images', 'mimes:jpeg,png,jpg,svg',
        ];
    }
}
