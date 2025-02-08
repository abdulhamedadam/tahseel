<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class student_registration extends Model
{
    use HasFactory;

    protected $fillable = ['child_name', 'email', 'date_birth', 'mother_phone', 'father_phone', 'home_phone',
        'father_national_number', 'mother_national_number', 'mother_job', 'father_job',
        'address', 'history_medical_ondition', 'unfavorite_food', 'other_information'];
    public $timestamps = true;
}
