<?php

namespace App\Http\Requests\Admin\subscription\offers;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOffer_R extends FormRequest
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
            'title_ar' => 'required|unique:sub_main_subscription,name->ar,'.$this->id,
            'title_en' => 'required|unique:sub_main_subscription,name->en,'.$this->id,
            'sub_type'=>'required',
            'customize_to'=>'required',
            'price'=>'required',
            'duration'=>'required',
            'details_en'=>'required',
            'details_ar'=>'required',
        ];
    }
}
