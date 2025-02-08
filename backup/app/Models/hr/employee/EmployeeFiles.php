<?php

namespace App\Models\hr\employee;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeFiles extends Model 
{

    protected $table = 'hr_employee_files';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('emp_id', 'type_file', 'status', 'file_path');

}