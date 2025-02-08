<?php

namespace App\Models\hr\operation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerformanceReport extends Model
{

    protected $table = 'hr_performance_report';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('emp_id', 'date_report', 'date_report_int', 'details');

}
