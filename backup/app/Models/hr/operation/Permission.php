<?php

namespace App\Models\hr\operation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model 
{

    protected $table = 'hr_permission';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('emp_id', 'start_permission',
     'end_permission', 'period', 'date_permission', 
     'date_permission_int', 'reason', 'status', 'year', 'month');

}