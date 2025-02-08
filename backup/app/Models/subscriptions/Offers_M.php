<?php

namespace App\Models\subscriptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Offers_M extends Model
{

    use HasFactory,HasTranslations,SoftDeletes;
    protected $table = 'sub_offers';
    protected $guarded   = [];
    public $translatable = ['name','details'];

    /************************************/
    public function save_data($request,$contract)
    {
        // dd('d');
        $data['name']=['ar'=>$request->title_ar,'en'=>$request->title_en];
        $data['details']=['ar'=>$request->title_ar,'en'=>$request->title_en];
        $data['offer_category'] =$request->sub_type;
        $data['customize_to'] =$request->customize_to;
        $data['price'] =$request->price;
        $data['duration'] =$request->duration;
        $data['contract'] =$contract;
        $data['status'] ='active';
        Offers_M::create($data);
    }

    /***********************************/
    public function update_data($request,$contract,$id)
    {
        $offer=Offers_M::find($id);
        $data['name']=['ar'=>$request->title_ar,'en'=>$request->title_en];
        $data['details']=['ar'=>$request->title_ar,'en'=>$request->title_en];
        $data['offer_category'] =$request->sub_type;
        $data['customize_to'] =$request->customize_to;
        $data['price'] =$request->price;
        $data['duration'] =$request->duration;
        $data['contract'] =$contract;
        $data['status'] ='active';
        $offer->update($data);

    }




}
