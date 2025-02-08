<?php

namespace App\Http\Resources;

use App\Http\Resources\Admin\MembersResource;
use App\Models\Master;
use App\Models\Members;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        if (!empty($this->user_image)) {
            $image_path = Storage::disk('images')->url($this->user_image);

            $image = asset((Storage::disk('images')->exists($this->user_image)) ? $image_path : 'assets/media/avatars/blank.png');
        } else {
            $image = asset('assets/media/avatars/blank.png');

        }

        return [
            'user_id'=>$this->id,
            'user_name'=>$this->user_name,
            'name'=>$this->name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'lang'=>$this->lang,
            'image'=>$image,
            'member_date'=>new MembersResource(Members::where('user_id', $this->id)->first()),
        ];
    }
}
