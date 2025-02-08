<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionsRequest extends FormRequest
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
            'title_en' => 'required|unique:permissions,title->en,'.$this->id,
            'title_ar' => 'required|unique:permissions,title->ar,'.$this->id,
            'name' => 'required|unique:permissions,name,'.$this->id,
            'guard_name' => 'required',
        ];
    }
}
