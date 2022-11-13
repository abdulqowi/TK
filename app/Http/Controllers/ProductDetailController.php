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
        try { 
            DB::transaction(function ()use($request) { 
                ProductDetails::create([
                    'price' => $request-> price,
                    'day' => $request-> date,
                ]);
            });
            return apiResponse(200,'success','success');
        }catch  (Exception $e) {
            return apiResponse(400,'error', $e);
        }

    }

    public function update(Request $request, $id){
        try { 
            DB::transaction(function ()use($request,$id) {
                ProductDetails::where('id',$id)->update([
                    'price' => $request-> price,
                    'day' => $request-> date,
                ]); 
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
    





















































