<?php

namespace App\Models;

use App\Models\subscriptions\SpecialSubscription_M;
use App\Models\subscriptions\MainSubscription_M;
use App\Models\subscriptions\Transportation_M;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembersSubscriptions extends Model
{
    use HasFactory;
    protected $table='tbl_member_subscriptions';
    protected $guarded=[];

    /********************************************/
    public function main_subscriptions()
    {
        return $this->belongsTo(MainSubscription_M::class, 'subscription_id');
    }
    public function special_subscriptions()
    {
        return $this->belongsTo(SpecialSubscription_M::class, 'subscription_id');
    }

    public function member()
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    /*********************************************/
    public function transportation()
    {
        return $this->belongsTo(Transportation_M::class, 'transport_id', 'id');
    }
    /**********************************************/
    public function additional_subscriptions()
    {
        return $this->hasMany(AdditionalMemberSubscriptions::class, 'member_subscription_id');
    }
}
