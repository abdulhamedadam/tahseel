<?php

namespace App\Models;

use App\Models\hr\employee\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainers extends Model
{
    use HasFactory;
    protected $table='trainers';
    protected $guarded=[];

    /********************************/
    public function employee()
    {
        return $this->belongsTo(Employee::class,'emp_id');
    }

    /*********************************/
    public function additional_subscriptions()
    {
        return $this->belongsTo(AdditionalMemberSubscriptions::class);
    }
}
