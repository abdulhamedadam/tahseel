<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;

class StudentRegistrationRequest extends FormRequest
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
            'child_name' => 'required',
            'email' => 'required|unique:student_registrations,email,'.$this->id,
            'father_phone' => 'required|unique:student_registrations,father_phone,'.$this->id,
            'mother_phone' => 'required|unique:student_registrations,mother_phone,'.$this->id,
            'home_phone' => 'required|unique:student_registrations,home_phone,'.$this->id,
            'mother_national_number' => 'required|unique:student_registrations,mother_national_number,'.$this->id,
            'father_national_number' => 'required|unique:student_registrations,father_national_number,'.$this->id,
            'history_medical_ondition' => 'required',
            'unfavorite_food' => 'required',
            'address' => 'required',
            'father_job' => 'required',
            'mother_job' => 'required',
            'date_birth' => 'required',

        ];
    }
}
