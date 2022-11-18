<?php

namespace App\Http\Controllers;
use Exception;
use App\Master;
use App\Transaction;
use App\User;
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

    public function store(){
        try {
            $count = User::count();
            for ($i=1; $i<$count; $i++) {
                Transaction::create([
                    'user_id' => $i,
                    'price' => request('price'),
                    'status' => request('status'),
                ]);    
            }
            $data = Transaction::get();
            return apiResponse( 200 , 'success', ' list :', $data); 
        } catch (Exception  $e) {
            return apiResponse(400,'error ', 'error', $e);
        }
    }


}
