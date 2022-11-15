<?php

namespace App;

use App\Master;
use App\UserDetail;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table ='schedules'; 
    protected $fillable = ['user_id' ,'day','status'];

    public function detail(){
        $this->hasMany(UserDetail::class, 'user_id');
    }

    public function master(){
        $this->hasMany(Master::class,'masters_id');
    }
}
