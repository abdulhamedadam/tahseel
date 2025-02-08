<?php

namespace App\Models\hr\employee;

use App\Models\hr\setting\MainSetting;
use App\Models\Trainers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{

    protected $table = 'hr_employee';
    public $timestamps = true;
    protected static $CodeIncrement = 1000;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('emp_code','name', 'national_id', 'birthday', 'address', 'social_status', 'specialization', 'email', 'phone',
        'experience_year', 'date_hired', 'job_title', 'work_hour_day', 'work_month_day', 'holiday_emergency',
        'holiday_year', 'main_salary', 'image');


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            $last = static::orderBy('id', 'desc')->first();
            $lastCode = $last ? $last->emp_code : static::$CodeIncrement;

            $row->emp_code = $lastCode + 1;
        });
    }
    function jop_title_data()
    {
        return $this->belongsTo(MainSetting::class, 'job_title');
    }

    protected $appends = ['imageUrl'];

    public function getImageUrlAttribute()
    {
        if (!empty($this->image)) {
            $image_path = Storage::disk('images')->url($this->image);
            return (Storage::disk('images')->exists($this->image)) ? asset($image_path) : getDefultImage();
        } else {
            return getDefultImage();

        }
    }


    /***************************************
     * /********************************/
    public function trainer()
    {
        return $this->hasOne(Trainers::class,'emp_id');
    }

}
