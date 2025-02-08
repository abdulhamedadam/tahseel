<?php

namespace App\Models\subscriptions;

use App\Models\AdditionalMemberSubscriptions;
use App\Models\MembersSubscriptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class MainSubscription_M extends Model
{
    use SoftDeletes;
    use HasFactory,HasTranslations;

    protected $table     = 'sub_main_subscription';
    public $timestamps   = true;
    protected $guarded   = [];
    public $translatable = ['name','details','details_tag'];
    //protected $dates = ['deleted_at'];



    /************************************/
    public function save_data($request,$contract)
    {
       // dd('d');
        $data['name']=['ar'=>$request->title_ar,'en'=>$request->title_en];
        $data['details']=['ar'=>$request->details_ar,'en'=>$request->details_en];
        $data['details_tag']=['ar'=>$request->details_tag_ar,'en'=>$request->details_tag_en];
        $data['category'] =$request->sub_type;
        $data['max_discount'] =$request->max_discount;
        $data['max_freezing_days'] =$request->max_freezing_days;
        $data['customize_to'] =$request->customize_to;
        $data['price'] =$request->price;
        $data['duration'] =$request->duration;
        $data['contract'] =$contract;
        $data['status'] ='active';
        MainSubscription_M::create($data);
    }

    /***********************************/
    public function update_data($request,$contract,$id)
    {
        //dd($request);
        $mainSubscription=MainSubscription_M::find($id);
        $data['name']=['ar'=>$request->title_ar,'en'=>$request->title_en];
        $data['details']=['ar'=>$request->details_ar,'en'=>$request->details_en];
        $data['details_tag']=['ar'=>$request->details_tag_ar,'en'=>$request->details_tag_en];
        $data['category'] =$request->sub_type;
        $data['max_discount'] =$request->max_discount;
        $data['max_freezing_days'] =$request->max_freezing_days;
        $data['customize_to'] =$request->customize_to;
        $data['price'] =$request->price;
        $data['duration'] =$request->duration;
        $data['contract'] =$contract;
        $data['status'] ='active';
       // dd($data);
        $mainSubscription->update($data);

    }

    /********************************************/
    public function member_subscription()
    {
        return $this->hasMany(MembersSubscriptions::class);
    }
    /*************************************************/
    public function additional_subscriptions()
    {
        return $this->hasMany(AdditionalMemberSubscriptions::class);
    }
    /**********************************************/
    public function toArray()
    {
        $array = parent::toArray();

        foreach ($this->translatable as $field) {
            $array[$field] = $this->getTranslations($field);
        }

        return $array;
    }

}
