<?php

namespace App\Models\subscriptions;

use App\Models\MembersSubscriptions;
use App\Models\subscriptions\SubscriptionSettings_M;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
class Transportation_M extends Model
{

    protected $table = 'sub_transportation';
    public $timestamps   = true;
    protected $guarded   = [];

    public function car_type_setting()
    {
        return $this->hasOne(SubscriptionSettings_M::class, 'id');
    }

    /********************************************/
    public function members_subscriptions()
    {
        return $this->hasOne(MembersSubscriptions::class);
    }

    /*********************************************/
    public function save_data($request)
    {
        $data['moving_day'] = $request->moving_day;
        $data['trip_time']  = $request->trip_time;
        $data['moving_time']= $request->moving_time;
//        $data['car_type']   = $request->car_type;
        $data['persons_number']   = $request->persons_number;
        Transportation_M::create($data);
    }

    /**********************************************/
    public function update_data($request,$id)
    {
        $transportation_m=Transportation_M::find($id);
        $data['moving_day'] = $request->moving_day;
        $data['trip_time']  = $request->trip_time;
        $data['moving_time']= $request->moving_time;
//        $data['car_type']   = $request->car_type;
        $data['persons_number']   = $request->persons_number;
        $transportation_m->update($data);

    }



}
