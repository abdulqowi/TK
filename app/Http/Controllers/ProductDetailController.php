<?php

namespace App\Http\Controllers;

use App\ProductDetails;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    public function index(){
        $ProductDetails = ProductDetails::get();
        return apiResponse(200,'success', 'list',$ProductDetails);
    }

    public function store(Request $request){
        
    }
    




















































}
