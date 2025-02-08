<?php

namespace App\Models\subscriptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\subscriptions\Devices_M;
class DeviceExercises_M extends Model
{

    use SoftDeletes;
    use HasFactory;
    protected $table = 'sub_device_exercises';
    protected $fillable = ['name','link','link_id','numbers','exercise_level','device_code','groups'];
    public $timestamps = true;

    protected $dates = ['deleted_at'];

    public function device_code_data()
    {
        return $this->belongsTo(Devices_M::class,'device_code');
    }


}
