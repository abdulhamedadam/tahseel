<?php

namespace App\Models\hr\operation;

use App\Models\hr\setting\HolidaySetting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Holidays extends Model
{

    protected $table = 'hr_holidays';
    public $timestamps = true;

    use SoftDeletes, HasFactory;
//    public $translatable = ['type_holiday','reason'];

    protected $dates = ['deleted_at'];
    protected $fillable = array('emp_id', 'type_holiday_fk', 'num_days', 'date_start', 'date_start_int', 'date_end', 'date_end_int', 'reason', 'month', 'year');


    public function typeholiday()
    {
        return $this->belongsTo(HolidaySetting::class,'type_holiday_fk','id');
    }
}
