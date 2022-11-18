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
