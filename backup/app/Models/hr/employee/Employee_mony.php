<?php

namespace App\Models\hr\employee;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee_mony extends Model 
{

    protected $table = 'hr_employee_mony';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('emp_id', 'extra_type', 'value', 'status');

}