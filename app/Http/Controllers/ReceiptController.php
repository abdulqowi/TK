<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use App\Course;
use App\Receipt;
use App\ProductDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class ReceiptController extends Controller
{
    public function index(){
        $receipt = Receipt::with('user','course','product')->get();
        return apiResponse(200,'success','list',$receipt);
    }

    public function show($id){
        $receipt = Receipt::with('user','course','product')->findOrFail($id);
        return apiResponse(200,'success','list',$receipt);
    }
    public function store(Request $request,$id){
        $rules =[
            'quantity' => 'required',
        ];
        $message = [
            'quantity.required' => 'Quantity is required',
        ];
        $validator = Validator::make($rules, $message);
        $productDetails = ProductDetails::find($id);
        if ($validator->fails()){
            return apiResponse(422,'error', $validator->errors()->first());
        } try {
            DB::transaction(function ()use($request,$productDetails) {
                Receipt::create([
                    'user_id' => auth()->id(),
                    'product_details_id' => $productDetails->id,
                    
                    'quantity' => request('quantity'),
                 ]);
                 return apiResponse(200,'success', $request);
            });
        } catch (Exception $e){
            return apiResponse(400,'error',$e->getMessage());
        };
    }

    public function courseStore(){
        $rules =[
            'course_id' =>'required',
        ];
        $message = [
            'course_id.required' => 'Course is required',
        ];
        $validator = Validator::make($rules, $message);
        $course = Course::find(request('course_id'));
        if ($validator->fails()){
            return apiResponse(422,'error', $validator->errors()->first());
        } try {
            DB::transaction(function ()use($course) {
                Receipt::create([
                    'user_id' => auth()->id(),
                    'course_id' => $course->id,                    
                 ]);
                });
            return apiResponse(200,'success','list:', $course);
        }catch (Exception $e){
            return apiResponse(400,'error',$e->getMessage());
        };
    }
    

    
        
    




































































}