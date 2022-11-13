<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Receipt;

class Course extends Model
{
    protected $guarded = [];

    public function course(){
        return $this-> belongsTo(Course::class);   
    }
}
