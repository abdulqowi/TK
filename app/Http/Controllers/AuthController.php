<?php

namespace App\Http\Controllers;

use App\MasterPrice;
use App\User;
use Exception;
use App\UserDetail;
use App\Transaction;
use Laravel\Passport\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\RefreshToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $rules = [
            'email'     => 'required|email',
            'password'  => 'required|min:8',
        ];

        $message = [
            'email.required'    => 'Mohon isikan email anda',
            'email.email'       => 'Mohon isikan email valid',
            'password.required' => 'Mohon isikan password anda',
            'password.min'      => 'Password wajib mengandung minimal 8 karakter',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }

        $data = [
            'email'     => $request->email,
            'password'  => $request->password,
        ];

        if (!Auth::attempt($data)) {
            return apiResponse(400, 'error', 'Anda tidak terdaftar');
        }

        $token = Auth::user()->createToken('API Token')->accessToken;

        $data   = [
            'token'     => $token,
            'user'      => Auth::user()->detail,
        ];

        return apiResponse(200, 'success', 'berhasil login', $data);
    }

    public function register(Request $request)
    {
        try {
            $id = User::insertGetId([
                    'name'  => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone'         => $request->phone,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                UserDetail::create([
                    'user_id'       => $id    ,
                    'address'       => $request->address,
                ]);
            
                Transaction::create([
                    'user_id' => $id,
                    'status' => 'Belum Dibayar',
                    'price' => MasterPrice::first()->price,
                    
                ]);    

                $data = User:: where( 'id', $id)->first();
            return apiResponse(201, 'success', 'user berhasil daftar',$data);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
    public function logout()
    {
        if (Auth::user()) {
            $tokens = Auth::user()->tokens->pluck('id');

            Token::whereIn('id', $tokens)->update([
                'revoked' => true
            ]);
            RefreshToken::whereIn('access_token_id', $tokens)->update([
                'revoked' => true
            ]);
        }
        return apiResponse(200, 'success', 'berhasil logout');
    }
}
