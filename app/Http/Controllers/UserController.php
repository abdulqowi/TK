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
            'student_name' => 'required',
            'birthday '     => 'required',
            'birthplace'     => 'required',
            'father_job' => 'required',
            'father_degree' => 'required',
            'address' => ' required',
            'mother'     => 'required',
            'mother_phone' => 'required',
            'mother_email' => ' required',
            'mother_job' => 'required',
            'mother_degree' => 'required',
            'gender' => 'required',
        ];

        $message = [
            'parent_name.required'     => 'Mohon isikan nama anda',
            'email.required'    => 'Mohon isikan email anda',
            'email.email'       => 'Mohon isikan email valid',
            'email.unique'      => 'Email sudah terdaftar',
            'password.required' => 'Mohon isikan password anda',
            'password.min'      => 'Password wajib mengandung minimal 8 karakter',
            'phone.required'    => 'Mohon isikan nomor hp anda',
            'birthday.required' => 'Mohon isikan Tanggal Lahir',
            'birthplace.required' => 'Mohon isikan tempat lahir',
            'father_degree.required' => "Mohon isikan Pendidikan terakhir",
            'father_job.required' => "Mohon isikan pekerjaan",
            'mother.required'    => 'Mohon isikan nama Ibu',
            'mother_job.required' => "Mohon isikan pekerjaan",
            'mother_phone.required' => "Mohon isikan Nomor telepon",
            'mother_degree.required' => "Mohon isikan Pendidikan terakhir",
            'mother_email.email' => "Masukan Email",
            'gender.required' => "Masukan Gender",
            'address'       => "masukan address",
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'parent_name'  => $request->parent_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone'         => $request->phone,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                UserDetail::create([
                    'user_id'       => $user,
                    'address'       => $request->address,
                    'student_name'  => $request->student_name,
                    'gender'        => $request->gender,
                    'address '      => $request->address,
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

    public function update(Request $request, $id)
    {
        $rules = [
            'parent_name'      => 'required',
            'email'     => 'required|email|unique:users,email,' . $id,
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

        if ($validator->fails()) {
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
                    'mother_phone' => $request->mother_phone,
                    'updated_at'    => date('Y-m-d H:i:s')
                ]);
            });

            return apiResponse(202, 'success', 'user berhasil disunting');
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
}
