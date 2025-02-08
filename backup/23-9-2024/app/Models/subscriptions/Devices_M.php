<?php

namespace App\Models\subscriptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\subscriptions\Exercises_M;
use Illuminate\Support\Facades\Storage;

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
        return $this->belongsTo(SubscriptionSettings_M::class,'exercise_type');
    }
    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        $value = $this->image;
        if (!empty($value)) {
            $image_path = Storage::disk('images')->url($value);
            return asset((Storage::disk('images')->exists($value)) ? $image_path : 'assets/media/avatars/blank.png');
        } else {
            return asset('assets/media/avatars/blank.png');

        }
    }



}
