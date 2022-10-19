<?php

namespace App\Http\Controllers;

use App\User;
use App\UserDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index() {
        $user = User::get();

        return apiResponse(200, 'success', 'List user', $user);
    }

    public function destroy($id) {
        try {
            DB::transaction(function () use ($id) {
                User::where('id', $id)->delete();
            });

            return apiResponse(202, 'success', 'user berhasil dihapus :(');
        } catch(Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }

    public function show($id) {
        $user = UserDetail::where('id', $id)->first();
        if($user) {
            return apiResponse(200, 'success', '', $user);
        }

        return apiResponse(404, 'not found', 'User tidak ditemukan :(');
    }

    public function store(Request $request) {
        $rules = [
            'parent_name'      => 'required',
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

        if($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request) {
                $id = User::insertGetId([
                    'name'  => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                UserDetail::insert([
                    'user_id'       => $id,
                    'address'       => $request->address,
                    'phone'         => $request->phone,
                    'student_name'         => $request->student_name,
                    'created_at'    => date('Y-m-d H:i:s')
                ]);
            });

            return apiResponse(201, 'success', 'user berhasil daftar');
        } catch(Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }

    public function update(Request $request, $id) {
        $rules = [
            'parent_name'      => 'required',
            'email'     => 'required|email|unique:users,email,'.$id,
            'password'  => 'required|min:8',
        ];

        $message = [
            'parent_name.required'     => 'Mohon isikan nama anda',
            'email.required'    => 'Mohon isikan email anda',
            'email.email'       => 'Mohon isikan email valid',
            'email.unique'      => 'Email sudah terdaftar',
            'password.required' => 'Mohon isikan password anda',
            'password.min'      => 'Password wajib mengandung minimal 8 karakter',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $id) {
                User::where('id', $id)->update([
                    'parent_name'  => $request->parent_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone'         => $request->phone,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                UserDetail::where('user_id', $id)->update([
                    'address'       => $request->address,
                    'student_name' => $request->student_name,
                    'updated_at'    => date('Y-m-d H:i:s')
                ]);
            });

            return apiResponse(202, 'success', 'user berhasil disunting');
        } catch(Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
}
