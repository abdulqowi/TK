<?php

namespace App;

use App\UserDetail;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];
    public function detail(){
        $this->belongsTo(UserDetail::class, 'user_id');
    }

    public function user =  []
        $this->belongsTo(User::class);
    }

    public function
}
