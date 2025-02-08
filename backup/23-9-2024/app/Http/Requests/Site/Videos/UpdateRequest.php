<?php

namespace App\Http\Requests\Site\Videos;

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
            'title_ar' => 'required|unique:site_videos,title->ar,'.$this->id,
            'title_en' => 'required|unique:site_videos,title->en,'.$this->id,
            'main_image' =>  'sometimes', 'images', 'mimes:jpeg,png,jpg',
            'full_link' => 'required|url',
        ];
    }
}
