<?php

namespace App\Http\Controllers;

use Exception;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function index(){
        $bill = Transaction::get();
        return apiResponse('200', 'success','list:',$bill);
    }

    public function store(Request $request){
        try {

            $bill = Transaction::create([
                'user_id' => auth()->user()->id,
                'payment_date' => $request->payment_date,
                'status' => $request->status,
                'price' => $request->price,
            ]); 
            return apiResponse(200, 'success','list :', $bill);
        }
        
        catch(Exception $e) {
            return apiResponse(400, 'error', $e);
        }
        
    }

    public function update(Request $request,$id){
        try { 
        $bill = Transaction::where('id',$id)->update([
                'user_id' => auth()->user()->id,
                'payment_date' => $request->payment_date,
                'status' => $request->status,
                'price' => $request->price,
            ]); 
            return apiResponse(200,'success','berhasil  diedit',$bill);
        }catch  (Exception $e) {
            return apiResponse(200,'error','error',$e);
        }
    }

    public function destroy($id){
        try {
            Transaction::where('id',$id)->delete();
            return apiResponse(200,'success','Berhasil dihapus:');
        }catch (Exception $e) {
            return apiResponse(200,'error','error',$e);
        }
    }
}
