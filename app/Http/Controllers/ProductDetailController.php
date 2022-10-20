<?php

namespace App\Http\Controllers;

use Exception;
use App\ProductDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductDetailController extends Controller
{
    public function index(){
        $ProductDetails = ProductDetails::get();
        return apiResponse(200,'success', 'list',$ProductDetails);
    }

    public function store(Request $request){
        $rules = [
            'product_list' =>'required',
            'total_price' =>'required',
        ];
        $messages = [
            'product_list.required' => 'Name is required',
            'total_price.required' => 'total_price is required',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if ($validator->fails()){
            return apiResponse(400,'error', $validator->errors());
        }
        try { 
            DB::transaction(function ()use($request) { 
                ProductDetails::insertGetId([
                    'product_list' => $request->product_list,
                    'total_price' => $request->total_price,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            });
            return apiResponse(200,'success','success');
        }catch  (Exception $e) {
            return apiResponse(400,'error', $e);
        }

    }

    public function update(Request $request, $id){
        $rules = [
            'product_list' =>'required',
            'total_price' =>'required',
        ];
        $messages = [
            'product_list.required' => 'Name is required',
            'total_price.required' => 'total_price is required',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if ($validator->fails()){
            return apiResponse(400,'error', $validator->errors());
        }
        try { 
            DB::transaction(function ()use($request,$id) {
                ProductDetails::where('id',$id)->update([
                    'product_list' => $request->product_list,
                    'total_price' => $request->total_price,]); 
            });
            return apiResponse(200,'success','berhasil  diedit',$id);
            }catch  (Exception $e) {
                return apiResponse(200,'error','error',$e);
            }
    }
    public function destroy($id){
        try {
            DB::transaction(function ()use($id) {
                ProductDetails::where('id',$id)->delete();
            });
            return apiResponse(200,'success','Berhasil dihapus:');
        }catch (Exception $e) {
            return apiResponse(200,'error','error',$e);
        }
    }

    public function show($id){
        $id= ProductDetails::where('id',$id)->first();
        if( $id ){
        return apiResponse(200,'success', $id);
        }
        return apiResponse(404,'error','not found',$id);
    }
















}
    





















































