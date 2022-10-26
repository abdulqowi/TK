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
            'user'      => Auth::user()->details,
        ];

        return apiResponse(200, 'success', 'berhasil login', $data);
    }

    public function register(Request $request)
    {
        $rules = [
            'parent_name'=> 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8',
            'phone'     => 'required',
            'student_name' => 'required',
            'father_job' => 'required',
            'birthday'     => 'required',
            'birthplace' => 'required',
            'father_degree' => 'required',
            'address'=>'required',
            'mother'     => 'required',
            'mother_phone' => 'required',
            'mother_email' => ' required',
            'mother_job' => 'required',
            'mother_degree' => 'required',
            'gender' => 'required',

        ];

        $message = [
            'parent_name.required'=> 'Mohon isikan nama anda',
            'email.required'    => 'Mohon isikan email anda',
            'email.email'       => 'Mohon isikan email valid',
            'email.unique'      => 'Email sudah terdaftar',
            'password.required' => 'Mohon isikan password anda',
            'password.min'      => 'Password wajib mengandung minimal 8 karakter',
            'phone.required'    => 'Mohon isikan nomor hp anda',
            'father_degree.required' => "Mohon isikan Pendidikan terakhir",
            'father_job.required' => "Mohon isikan pekerjaan",
            'birthday.required' => 'Mohon isikan Tanggal Lahir',
            'birthplace.required' => 'Mohon isikan tempat lahir',
            'mother.required'    => 'Mohon isikan nama Ibu',
            'mother_job.required' => "Mohon isikan pekerjaan",
            'mother_phone.required' => "Mohon isikan Nomor telepon",
            'mother_degree.required' => "Mohon isikan Pendidikan terakhir",
            'mother_email.email' => "Masukan Email",
            'address'=>"Masukan alamat",
            'gender.required' => "Masukan Gender",
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request) {
                $id = User::insertGetId([
                    'parent_name'  => $request->parent_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone'         => $request->phone,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                UserDetail::insert([
                    'user_id'       => $id    ,
                    'address'       => $request->address,
                    'student_name'  => $request->student_name,
                    'gender'        => $request->gender,
                    'mother'        => $request->mother,
                    'mother_phone'         => $request->mother_phone,
                    'mother_email'         => $request->mother_email,
                    'mother_job'         => $request->mother_job,
                    'mother_degree'    => $request->mother_degree,
                    'father_job' => $request->father_job,
                    'birthday' => $request->birthday,
                    'birthplace' => $request->birthplace,
                    'father_degree' => $request->father_degree,
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
