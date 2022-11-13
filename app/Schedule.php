<?php

namespace App;

use App\Master;
use App\UserDetail;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $guarded = [];
    public function detail(){
        $this->belongsTo(UserDetail::class, 'user_id');
    }

    public function master(){
        $this->belongsTo(Master::class);
    }
}
