<?php

use App\User;
use App\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = ['user_id'];

    // public function details(){
    //     return $this->belongsToMany(User::class);
    // }

    public function user(){
        return $this->belongsTo(User::class);
    }
    // public function course(){
    //     return $this->belongsToMany(Course::class);
    // }
    // public function product(){
    //     return $this->belongsToMany(Product::class);
    // }   
}