<?php

namespace App\Http\Controllers;
use App\User;
use Exception;

use App\Master;
use App\Transaction;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
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
        $user= User::where('id', Auth::user()->id)->first();
        // dd($this->id->name);
        $request = Transaction::where('id', $user->id)->first();
        // dd($request);
        $client = new Client();
        $url = 'https://api.sandbox.midtrans.com/v2/charge';
                $json = [
                    'payment_type' => 'gopay',
                    'transaction_details' => [
                        'order_id' => $request->id . Str::random(8),
                        'gross_amount' => $request->price,
                    ],
                
                    "gopay" => [
                        "enable_callback"=> true,
                        "callback_url"=> "someapps://callback"
                    ],
                    'customer_details' => [
                        'first_name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        // 'adress' => $user->user_detail->address,
                    ],
                    
                    'item_details' => [
                        [
                            'name' => 'Pembayaran Tagihan Sampah',
                            'price' =>$request->price,
                            'quantity' => 1,
                        ],
                    ],
                ];
                $headers = [
                    'Accept'=>'appliaction/json',
                    'Authorization'=>'Basic ' ,
                    'Content-Type'=>'application/json',
                ];
                $response = $client ->request('post',$url,[
                    'auth' => ['SB-Mid-server-Jkq5V28iAslT9qVGRIlEcV8d', ''],
                    'headers' =>$headers,
                    'access-control-allow-origin' =>'*',
                    'json'=> $json,
                ]);
        // merapihkan json
                $data = json_decode($response->getBody(),true);
                if ($data['meta'] = 200) {
                    Transaction::where('id', $request->id)->update([
                        'status' => 'Menunggu Verifikasi',
                    ]);
                return apiResponse(202, 'success', 'berhasil bayar',$data);
                    }
        }catch (Exception $e){
            return apiResponse (400, 'error', 'error', $e);
        }
    }

    public function update(Request $request,$id){
        try { 
        Transaction::where('id',$id)->update([
                    'status' => $request->status,
                ]); 
                $data = Transaction::where('id',$id)->first();
            return apiResponse(200,'success','berhasil  diedit',$data);
        }catch  (Exception $e) {
            return apiResponse(200,'error','error',$e);
        }
    }

}
