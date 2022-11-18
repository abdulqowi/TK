<?php

namespace App\Http\Controllers;

use Exception;
use App\Master;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function index(){
        $transaction = Transaction::get();
        return apiResponse(200,'success', 'list:', $transaction);
    }

    public function show($id){
        try {
            $receipt = Transaction::findOrFail($id);
            return apiResponse(200,'success','list',$receipt);
        } catch (\Exception $e) {
            return apiResponse(404,'error',$e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
        Transaction::where('id', $id)->delete();
        return apiResponse(200,'success ', 'list :', $id);
        }catch (Exception $e) {
            return apiResponse(400,'error ', 'list :', $e);
        }
    }

    // public function store(){
    //     try {
    //         Transaction::create([
    //             'user_id' => auth()->user()->id,
    //             'status' => request('status '),
    //         ]);
    //         return apiResponse( 200 , 'success', ' list :', $master ); 
    //     } catch (Exception  $e) {
    //         return apiResponse(400,'error ', 'store :', $e);
    //     }
    // }

}
