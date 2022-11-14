<?php

namespace App\Http\Controllers;

use Exception;
use App\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EducationsController extends Controller
{
    //show all edukasi
    public function index()
    {
        $edu = Education::get();

        return apiResponse(200, 'success', 'List Edukasi', $edu);
    }

    //delete edukasi
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                Education::where('id', $id)->delete();
            });

            return apiResponse(202, 'success', 'data berhasil dihapus');
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }

    //create a new educations 
    public function store(Request $request)
    {
        // $rules = [
        //     'user_id' => 'required',
        //     'title' => 'required',
        //     'content' => 'required',
        //     'desc' => 'required',
        //     'image' => 'required',
        // ];
        // $validator = Validator::make($request->all(), $rules);
        // if ($validator->fails()) {
        //     return apiResponse(400, 'error', 'error', $validator->errors());
        // }
        try {
            $extension = $request->file('image')->getClientOriginalExtension();
            $image = strtotime(date('Y-m-d H:i:s')).'.'.$extension;
            $destination = base_path('public/images/Register');
            $request->file('image')->move($destination,$image);

            DB::transaction(function () use ($request) {
                Education::insert([
                    'user_id' => $request->user_id,
                    'title' => $request->title,
                    'content' => $request->content,
                    'desc' => $request->desc,
                    'image' => $request->image,
                    'created_at' => date ('Y-m-d H:i:s')
                ]);
            });
            return apiResponse(201, 'success', 'berhasil ditambah');
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }

    //Edit educations
    public function update(Request $request, $id)
    {
        $rules = [
            'user_id' => 'required',
            'title' => 'required',
            'content' => 'required',
            'desc' => 'required',
            'image' => 'required',
        ];
        $message = [
            'user_id.required' => 'Enter user id',
            'title.required' => 'Enter title',
            'content.required' => 'Enter content',
            'desc.required' => 'Enter desc',
            'image.required' => 'Enter image',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return apiResponse(400, 'error', 'error', $validator->errors());
        }
        try {
            $extension = $request->file('image')->getClientOriginalExtension();
            $image = strtotime(date('Y-m-d H:i:s'));
            $destination = base_path('public/images/');
            $request->file('image')->move($destination,$image);

            DB::transaction(function () use ($request, $id, $image) {
                Education::where('id', $id)->update([
                    'user_id' => $request->user_id,
                    'title' => $request->title,
                    'content' => $request->content,
                    'desc' => $request->desc,
                    'image' => $image,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            });
            return apiResponse(202,'success', 'berhasil diedit');
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
}
