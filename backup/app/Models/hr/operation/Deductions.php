<?php

namespace App\Models\hr\operation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deductions extends Model 
{

    protected $table = 'hr_deductions';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('emp_id', 'value', 'date_deductions', 'date_bonuses_int', 'month', 'year', 'reason');

}