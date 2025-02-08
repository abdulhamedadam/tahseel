<?php

namespace App\Models\subscriptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Spatie\Translatable\HasTranslations;

class Exercises_M extends Model
{

    use SoftDeletes;
    use HasFactory;
    protected $table = 'sub_exercises';
    public $timestamps = true;
    protected $dates = ['deleted_at'];


    public function exercise_type()
    {
        return $this->hasMany(SubscriptionSettings_M::class, 'exercise_type');
    }

    public function exercise_level()
    {
        return $this->hasMany(SubscriptionSettings_M::class, 'exercise_level');
    }

}
