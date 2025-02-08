<?php

namespace App\Models;

use App\Models\hr\employee\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Trainers extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
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
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

}
