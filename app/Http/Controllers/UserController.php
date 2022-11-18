<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use App\UserDetail;
use App\Transaction;
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
        $user = User::where('id', $id)->first();
        if ($user) {
            $user->user_detail->image = $user->user_detail->imagePath;
            return apiResponse(200, 'success', '', $user);
        }

        return apiResponse(404, 'not found', 'User tidak ditemukan :(');
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
            $update = UserDetail::where('id',$this->id)->get();
            return apiResponse(202, 'success', 'user berhasil disunting',$update);
        }catch (Exception $e){
            return apiResponse (400, 'error', 'error', $e);
        }
    }

    
}
