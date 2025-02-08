<?php

namespace App\Models;

use App\Models\subscriptions\SubscriptionSettings_M;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Members extends Model
{
    use HasFactory;
    protected $table     = 'tbl_members';
    public $timestamps   = true;
    protected $guarded   = [];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        $value = $this->member_image;
        if (!empty($value)) {
            $image_path = Storage::disk('images')->url($value);
            return asset((Storage::disk('images')->exists($value)) ? $image_path : 'assets/media/avatars/blank.png');
        } else {
            return asset('assets/media/avatars/blank.png');

        }
    }

    /*****************************************/
    public function goals()
    {
        return $this->hasMany(MembersGoals::class,'member_id');
    }

    public function health_status()
    {
        return $this->belongsTo(SubscriptionSettings_M::class,'health_status_id');

    }
    /****************************************/
    public function inbody()
    {
        return $this->hasMany(MembersInbody::class,'member_id');
    }
    /******************************************/
    public function members_subscriptions()
    {
        return $this->hasMany(MembersSubscriptions::class,'member_id');
    }

}
