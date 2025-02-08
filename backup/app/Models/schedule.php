<?php

namespace App\Models;

use App\Models\hr\employee\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Trainers;
use App\Models\subscriptions\Exercises_M;

class schedule extends Model
{
    use HasFactory;
    protected $table='schedules';
    protected $fillable = ['trainer_id','class_id','time','date'];

    /********************************/
   
    public function trainers()
    {
     return $this->belongsTo(Trainers::class,'trainer_id');

    }

    function exercise_type_rel()
    {
        return $this->belongsTo(Exercises_M::class,'class_id');
    }


}
