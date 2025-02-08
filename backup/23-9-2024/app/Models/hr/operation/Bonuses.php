<?php

namespace App\Models\hr\operation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bonuses extends Model
{

    protected $table = 'hr_bonuses';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('emp_id', 'value',
     'date_bonuses', 'date_bonuses_int', 'month', 'year', 'reason');

}
