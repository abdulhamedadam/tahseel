<?php

namespace App\Http\Requests\Admin\subscription\main_subscription;

use Illuminate\Foundation\Http\FormRequest;

class SaveMainSubsacription_R extends FormRequest
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
            'title_ar'=>'required',
            'title_en'=>'required',
            'sub_type'=>'required',
            'customize_to'=>'required',
            'price'=>'required',
            'duration'=>'required',
            'details_en'=>'required',
            'details_ar'=>'required',
            'max_discount'=>'required',
        ];
    }
}
