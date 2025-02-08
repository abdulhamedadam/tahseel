<?php

namespace App\Models\subscriptions;


//use App\Models\MembersSubscriptions;
use App\Models\AdditionalMemberSubscriptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class SpecialSubscription_M extends Model
{

    use HasFactory,HasTranslations;

    protected $table     = 'sub_special_subscription';
    protected $guarded   = [];
    public $timestamps   = true;
    public $translatable = ['name','details'];
  //  protected $fillable  = array('price');

    public function exercise_type()
    {
        return $this->hasMany('SubscriptionSettings_M', 'exercise_type');
    }

    public function exercise_level()
    {
        return $this->hasMany('SubscriptionSettings_M', 'exercise_level');
    }

    /********************************************/
    public function member_subscription()
    {
        return $this->belongsTo(MembersSubscriptions::class,'subscription_id');
    }
    /********************************************/
    public function additional_subscriptions()
    {
        return $this->belongsTo(AdditionalMemberSubscriptions::class);
    }

}
