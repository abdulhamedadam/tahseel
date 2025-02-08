<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembersInbody extends Model
{
    use HasFactory;
    protected $table='tbl_member_inbody';
    protected $guarded=[];

    /***********************************/
    public function member()
    {
        return $this->belongsTo(Members::class,'member_id');
    }
}
