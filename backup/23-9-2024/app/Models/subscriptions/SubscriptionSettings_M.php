<?php

namespace App\Models\subscriptions;

use App\Models\MembersGoals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class SubscriptionSettings_M extends Model
{
    use HasFactory,HasTranslations,SoftDeletes;
    protected $table = 'sub_settings';
    protected $guarded = [];
    public $timestamps = true;
    public $translatable = ['title'];


    /*****************************************/
    public function transportation()
    {
        return $this->belongsTo(Transportation_M::class);
    }
    /*****************************************/
    public function save_settings($request)
    {
        $data['ttype']=$request->type;
        $data['title']=['ar'=>$request->title_ar,'en'=>$request->title_en];
        //dd($data);
        SubscriptionSettings_M::create($data);

    }

    /****************************************/
    public function update_settings($request,$id)
    {
        $data['ttype']=$request->type;
        $data['title']=['ar'=>$request->title_ar,'en'=>$request->title_en];
        //dd($data);
        SubscriptionSettings_M::where('id',$id)->update($data);

    }

    /*******************************************/
    public function goals()
    {
        return $this->hasMany(MembersGoals::class);
    }

}
