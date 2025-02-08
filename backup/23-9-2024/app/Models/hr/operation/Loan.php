<?php

namespace App\Models\hr\operation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{

    protected $table = 'hr_loan';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('emp_id', 'loan_type', 'value',
    'installments_num', 'date_loan', 'date_loan_int', 'reason',
     'month', 'year', 'date_deductions', 'date_deductions_int');

}
