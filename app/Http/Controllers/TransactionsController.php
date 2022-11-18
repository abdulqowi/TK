<?php

namespace App\Http\Controllers;
use App\User;
use Exception;

use App\Master;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionsController extends Controller
{
    public function index(){
        $bill = Transaction::get();
        foreach($bill as $data){
            $data -> image = asset('public/images/Register') . '/' .$data -> image;
        }
        return apiResponse('200', 'success','list:',$bill);
    }

    public function show($id){
        try {
            $bill = Transaction::where('id',$id) ->first();
            return apiResponse('200', 'success','list:',$bill);
        } catch (\Exception $e) {
            return apiResponse(404, 'error',' error', $e);
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

    public function payment(Request $request)
    {
        try {
            $this->id = Auth::user()->id;
            if ($request->has('image')) {
                $oldImage = Auth::user()->detail->image;
    
                if ($oldImage) {
                    $pleaseRemove = base_path('public/assets/images/transaction/') . $oldImage;
    
                    if (file_exists($pleaseRemove)) {
                        unlink($pleaseRemove);
                    }
                }
    
                $extension = $request->file('image')->getClientOriginalExtension();
    
                $name = date('YmdHis') . '' . $this->id . '.' . $extension;
    
                $path = base_path('public/assets/images/transaction');
    
                $request->file('image')->move($path, $name);
    
                Transaction::where('user_id', $this->id)->update([
                    'image' => $name,
                ]);
            }
            $update = Transaction::where('id',$this->id)->get();
            return apiResponse(202, 'success', 'user berhasil disunting',$update);
        }catch (Exception $e){
            return apiResponse (400, 'error', 'error', $e);
        }
    }

}
