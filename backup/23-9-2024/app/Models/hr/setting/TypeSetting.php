<?php

namespace App\Models\hr\setting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class TypeSetting extends Model
{

    protected $table = 'hr_type_setting';
    public $timestamps = true;

    use SoftDeletes,HasTranslations;
    public $translatable = ['title'];

    protected $dates = ['deleted_at'];
    protected $fillable = array('title', 'code');

}
