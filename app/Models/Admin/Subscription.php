<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table   ='tbl_subscriptions';
    protected $guarded = [];

    public function add_subscription_data($request)
    {
        $data['name']  =$request->name;
        $data['price']  =$request->price;
        $data['description']  =$request->description;

        return $data;
    }

}
