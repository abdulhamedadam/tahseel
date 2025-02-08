<?php

namespace App\Models\subscriptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
class Task_Management extends Model
{
    use HasFactory,HasTranslations;
    protected $table = 'Task_Managements';
    public $translatable = ['title','details'];
    protected $fillable = ['title','emp_id','details','type','date','status'];
    public $timestamps = true;
}
