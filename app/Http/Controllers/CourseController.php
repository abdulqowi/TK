<?php

namespace App\Http\Controllers;

use Exception;
use App\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index()
    {
        $course = Course::get();

        return apiResponse(200, 'success', 'List user', $course);
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                Course::where('id', $id)->delete();
            });

            return apiResponse(202, 'success', 'user berhasil dihapus :(');
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }

    public function store(Request   $request)
    {
        $rules = [
            'course_name' => 'required',
            'total_price' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return apiResponse(400, 'error', 'error', $validator->errors());
        }
        try {
            DB::transaction(function () use ($request) {
                $id = Course::insertGetId([
                    'course_name' => $request->course_name,
                    'total_price' => $request->total_price,
                    'created_at' => date ('Y-m-d H:i:s')
                ]);
            });
            return apiResponse(200, 'success', 'berhasil ditambah:');
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'course_name' => 'required',
            'total_price' => 'required'
        ];
        $message = [
            'course_name' => 'Enter course name',
            'total_price' => 'Enter total price'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return apiResponse(400, 'error', 'error', $validator->errors());
        }
        try {
            DB::transaction(function () use ($request, $id) {
                $id = Course::where('id', $id)->update([
                    'course_name' => $request->course_name,
                    'total_price' => $request->total_price,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            });
            return apiResponse(200,'success', 'berhasil diedit',$id);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
}
