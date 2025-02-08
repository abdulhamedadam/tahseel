<?php

namespace App\Http\Resources\subscriptions;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Lang;

class MainSubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /*if (!empty($this->image)) {
                $image_path = Storage::disk('images')->url($this->image);

                $imageurl = asset((Storage::disk('images')->exists($this->image)) ? $image_path : 'assets/images/blank.png');
            } else {
                $imageurl = asset('assets/images/blank.png');

            }*/
        $Details_tag = $this->getTranslations('details_tag');
        $Details_tag['en'] = json_decode($Details_tag['en']);
        $Details_tag['ar'] = json_decode($Details_tag['ar']);
        /*$sub_type_arr = [
            'monthly' => trans('sub.monthly'),
            'quarter' => trans('sub.quarter'),
            'semi' => trans('sub.semi'),
            'annual' => trans('sub.annual'),
        ];*/
        $sub_type_arr = [
            'monthly' => [
                'ar'=>Lang::get('sub.monthly', [], 'ar'),
                'en'=>Lang::get('sub.monthly', [], 'en'),
            ],
            'quarter' => [
                'ar'=>Lang::get('sub.quarter', [], 'ar'),
                'en'=>Lang::get('sub.quarter', [], 'en'),
            ],
            'semi' => [
                'ar'=>Lang::get('sub.semi', [], 'ar'),
                'en'=>Lang::get('sub.semi', [], 'en'),
            ],
            'annual' => [
                'ar'=>Lang::get('sub.annual', [], 'ar'),
                'en'=>Lang::get('sub.annual', [], 'en'),
            ],
        ];


        $category = $sub_type_arr[$this->category];
        return [
            'title' => $this->getTranslations('name'),
            'Details' => $this->getTranslations('details'),
//            'Details_tag' => $this->getTranslations('details_tag'),
            'Details_tag' => $Details_tag,
            'Duration' => $this->duration,
            'price' => $this->price,
            'category' => $category,
            'isBast' => false,
//            'Image' => $imageurl,
        ];
    }
}
