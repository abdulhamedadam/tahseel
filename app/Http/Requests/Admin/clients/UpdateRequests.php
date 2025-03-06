<?php

namespace App\Http\Requests\Admin\clients;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequests extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            // 'client_code'          => 'required|string|max:255|unique:tbl_clients,client_code,'.$id,
            'name'                 => 'required|string|max:255',
            'phone'                => 'required|string|max:15',
            // 'email'                => 'nullable|email|max:255',
            'user'                 => 'required|string|max:255',
            'address1'             => 'nullable|string|max:255',
            // 'address2'             => 'nullable|string|max:255',
            'box_switch'           => 'nullable|string|max:255',
            'client_type'          => 'required|in:satellite,internet',
            'image'                => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'commercial_register'  => 'nullable|numeric|digits_between:1,15',
            'subscription_id'      => 'required|integer',
            'price'                => 'required',
            'subscription_date'    => 'required|date',
            'start_date'           => 'required|date|after_or_equal:subscription_date',
            'notes'                => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'client_code.required'          => trans('clients.client_code_required'),
            'client_code.unique'            => trans('clients.client_code_unique'),
            'name.required'                 => trans('clients.name_required'),
            'phone.required'                => trans('clients.phone_required'),
            'email.email'                   => trans('clients.email_invalid'),
            'image.image'                   => trans('clients.image_invalid'),
            'image.mimes'                   => trans('clients.image_format_invalid'),
            'image.max'                     => trans('clients.image_size_invalid'),
            // 'commercial_register.numeric'   => trans('clients.commercial_register_numeric'),
            'governate.required'            => trans('clients.governate_required'),
            'governate.exists'              => trans('clients.governate_invalid'),
            'city.exists'                   => trans('clients.area_invalid'),
            'notes.max'                     => trans('clients.notes_too_long'),
        ];
    }

}
