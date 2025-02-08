<?php

namespace App\Models\subscriptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\subscriptions\Exercises_M;

//use Spatie\Translatable\HasTranslations;

class Devices_M extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'sub_devices';
    protected $fillable = ['image','name','code','exercise_type','description'];
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    function exercise_type_rel()
    {
        return $this->belongsTo(Exercises_M::class,'exercise_type');
    }



}
