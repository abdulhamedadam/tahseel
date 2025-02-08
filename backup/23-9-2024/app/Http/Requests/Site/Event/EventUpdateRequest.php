<?php

namespace App\Http\Requests\Site\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventUpdateRequest extends FormRequest
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
            'title_ar' => 'required|unique:site_events,title->ar,'.$this->id,
            'title_en' => 'required|unique:site_events,title->en,'.$this->id,
            'details_ar' => 'required',
            'details_en' => 'required',
            'publisher' => 'required',
            'date_at' => 'required|date',
            'main_image' =>  'sometimes', 'images', 'mimes:jpeg,png,jpg',
            'images.*' => 'sometimes|mimes:jpg,jpeg,png',
        ];
    }
}
