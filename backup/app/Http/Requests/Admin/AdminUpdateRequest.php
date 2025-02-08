<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_name' => 'required|unique:admins,name,'.$this->id,
            'phone' => 'required|unique:admins,phone,'.$this->id,
//            'password' => 'required',
            'email' => 'required|email|unique:admins,email,'.$this->id,
            'status' => 'required',
        ];
    }
}
