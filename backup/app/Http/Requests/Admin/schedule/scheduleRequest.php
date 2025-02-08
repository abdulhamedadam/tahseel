<?php

namespace App\Http\Requests\Admin\schedule;

use Illuminate\Foundation\Http\FormRequest;

class scheduleRequest extends FormRequest
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
            'trainer_id'=>'required',
            'class_id'=>'required',
            'date'=>'required',
            'time'=>'required',
          //  'class_id'=>'required',


        ];
    }
}
