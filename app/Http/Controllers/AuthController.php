<?php

namespace App\Http\Controllers;

use App\User;
use App\UserDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

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
            return apiResponse(400, 'error', 'Data tidak terdaftar, akun bodong ya? Fuck u');
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
        $rules = [
            'name'=> 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8',
            'phone'     => 'required',
        ];

        $message = [
            'name.required'     => 'Mohon isikan nama anda',
            'email.required'    => 'Mohon isikan email anda',
            'email.email'       => 'Mohon isikan email valid',
            'email.unique'      => 'Email sudah terdaftar',
            'password.required' => 'Mohon isikan password anda',
            'password.min'      => 'Password wajib mengandung minimal 8 karakter',
            'phone.required'    => 'Mohon isikan nomor hp anda',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request) {
                $id = User::insertGetId([
                    'parent_name'  => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone'         => $request->phone,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                UserDetail::insert([
                    'user_id'       => $id,
                    'address'       => $request->address,
                    'student_name' => $request->student_name,
                    'created_at'    => date('Y-m-d H:i:s')
                ]);
            });

            return apiResponse(201, 'success', 'user berhasil daftar');
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
