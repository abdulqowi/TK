<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use App\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index()
    {
        $user = User::get();

        return apiResponse(200, 'success', 'List user', $user);
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                User::where('id', $id)->delete();
            });

            return apiResponse(202, 'success', 'user berhasil dihapus :(');
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }

    public function show($id)
    {
        $user = User::with('user_detail', 'receipt')->where('id', $id)->first();
        if ($user) {
            return apiResponse(200, 'success', '', $user);
        }

        return apiResponse(404, 'not found', 'User tidak ditemukan :(');
    }

    public function store(Request $request)
    {
        $rules = [
            'parent_name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8',
            'phone'     => 'required',
            'address' => ' required',
        ];

        $message = [
            'parent_name.required'     => 'Mohon isikan nama anda',
            'email.required'    => 'Mohon isikan email anda',
            'email.email'       => 'Mohon isikan email valid',
            'email.unique'      => 'Email sudah terdaftar',
            'password.required' => 'Mohon isikan password anda',
            'password.min'      => 'Password wajib mengandung minimal 8 karakter',
            'phone.required'    => 'Mohon isikan nomor hp anda',
            'address'       => "masukan address",
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name'  => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone'         => $request->phone,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                UserDetail::create([
                    'user_id'       => $user,
                    'address'       => $request->address,
                ]);
            });

            return apiResponse(201, 'success', 'user berhasil daftar');
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }

    public function update(Request $request)
    {
        try {
            $this->id = Auth::user()->id;
            User::where('id', $this->id)->update([
                'name'  => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone'         => $request->phone,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            UserDetail::where('user_id', $this->id)->update([
                'address'       => $request->address,
                'updated_at'    => date('Y-m-d H:i:s')
            ]);
            if ($request->has('image')) {
                $oldImage = Auth::user()->detail->image;

                if ($oldImage) {
                    $pleaseRemove = base_path('public/assets/images/user/') . $oldImage;

                    if (file_exists($pleaseRemove)) {
                        unlink($pleaseRemove);
                    }
                }

                $extension = $request->file('image')->getClientOriginalExtension();

                $name = date('YmdHis') . '' . $this->id . '.' . $extension;

                $path = base_path('public/assets/images/user');

                $request->file('image')->move($path, $name);

                UserDetail::where('user_id', $this->id)->update([
                    'image' => $name,
                ]);
            }
            $update = UserDetail::where('id',$this->id)->first();

            return apiResponse(202, 'success', 'user berhasil disunting',$update);
        }catch (Exception $e){
            return apiResponse (400, 'error', 'error', $e);
        }
    }
}
