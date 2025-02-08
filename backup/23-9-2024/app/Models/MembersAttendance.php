<?php

namespace App\Models;

use App\Models\subscriptions\MainSubscription_M;
use App\Models\subscriptions\SpecialSubscription_M;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembersAttendance extends Model
{
    use HasFactory;
    protected $table='members_attendance';
    protected $guarded=[];



    /*********************************************************/
    public function additional_subscription()
    {
        return $this->belongsTo(AdditionalMemberSubscriptions::class);
    }

    /********************************************************/
    public function main_subscriptions()
    {
        return $this->belongsTo(MainSubscription_M::class, 'subscription_id');
    }
    /*******************************************************/
    public function special_subscriptions()
    {
        return $this->belongsTo(SpecialSubscription_M::class, 'subscription_id');
    }
}
