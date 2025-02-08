<?php

namespace App\Models;

use App\Models\subscriptions\SubscriptionSettings_M;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembersGoals extends Model
{
    use HasFactory;
    protected $table='tbl_members_goals';
    protected $guarded=[];


    /*****************************************/
    public function member()
    {
        return $this->belongsTo(Members::class,'member_id','id');
    }
    /*****************************************/
    public function subsetting_goals()
    {
        return $this->belongsTo(SubscriptionSettings_M::class,'goal_id');

    }
}
