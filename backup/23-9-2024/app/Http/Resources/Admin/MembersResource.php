<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MembersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (!empty($this->member_image)) {
            $image_path = Storage::disk('images')->url($this->member_image);
            $imageurl = asset((Storage::disk('images')->exists($this->member_image)) ? $image_path : 'assets/images/blank.png');
        } else {
            $imageurl = asset('assets/images/blank.png');

        }

        return [
          'memberId'=>$this->id,
          'memberName'=>$this->member_name,
          'memberEmail'=>$this->email,
          'memberPhone'=>$this->phone,
          'country_code'=>$this->country_code,
          'phone_full'=>$this->phone_full,
            'memberGoalData'=>$this->goals,
        //  'memberGoal'=>$this->goals->goal_id,
          'memberBirthDate'=>$this->birth_date,
          'memberHeight'=>$this->height,
          'memberWeight'=>$this->weight,
          'memberFatPercentage'=>$this->fat_percentage,
          'memberTargetWeight'=>$this->target_weight,
          'memberHealthStatus'=>$this->health_status_id,
          'memberContractBnod'=>$this->contract_bnod,
          'memberImage'=>$imageurl,
        ];
    }


    public function edite_data($request): array
    {

        if (!empty($this->member_image)) {
            $image_path = Storage::disk('images')->url($this->member_image);
            //dd($image_path);
            $imageurl = asset((Storage::disk('images')->exists($this->member_image)) ? $image_path : 'assets/images/blank.png');

        } else {
            $imageurl = asset('assets/images/blank.png');

        }

        return [
            'memberId'=>$this->id,
            'memberName'=>$this->member_name,
            'memberEmail'=>$this->email,
            'memberPhone'=>$this->phone,
            'memberGoal'=>$this->goals->pluck('goal_id')->toArray(),
            'memberGoalData'=>$this->goals,
            'memberBirthDate'=>$this->birth_date,
            'memberHeight'=>$this->height,
            'memberWeight'=>$this->weight,
            'memberFatPercentage'=>$this->fat_percentage,
            'memberTargetWeight'=>$this->target_weight,
            'memberHealthStatus'=>$this->health_status_id,
            'memberContractBnod'=>$this->contract_bnod,
            'memberImage'=>$imageurl,
            'country_code'=>$this->country_code,
            'phone_full'=>$this->phone_full,
        ];
    }
}
