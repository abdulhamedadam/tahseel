<?php

namespace App\Http\Requests\hr\employee;

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
            'name' => [
                'required',
                Rule::unique('hr_employee', 'name')->ignore($this->id),
            ],
            'phone' => 'required|unique:hr_employee,phone,' . $this->id,
            'email' => 'required|unique:hr_employee,email,' . $this->id,
            'national_id' => 'required|unique:hr_employee,national_id,' . $this->id,
            'birthday' => 'required',
//            'address' => 'required',
//            'job_title' => 'required',
//            'specialization' => 'required',
            'main_salary' => 'required|numeric|min:0',
            'holiday_year' => 'required|numeric|min:0',
            'work_month_day' => 'required|numeric|min:0',
            'work_hour_day' => 'required|numeric|min:0',
//            'image' => 'sometimes|mimes:jpg,jpeg,png',
        ];
    }
}
