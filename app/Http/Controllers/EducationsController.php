<?php

namespace App\Http\Controllers;

use Exception;
use App\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EducationsController extends Controller
{
    //show all edukasi
    public function index()
    {
        $edu = Education::get();

        foreach($edu as $data){
            $data -> image = asset('/images/Register/'.$data -> image) ;
        }

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

                $id = Education::insert([
                    'user_id' => auth()->user()->id,
                    'title' => $request->title,
                    'content' => $request->content,
                    'image' => $request->image,
                    'created_at' => date ('Y-m-d H:i:s')
                ]);
            $update = Education::where('id',$id)->first();
            $update -> image = asset('/images/Register') . '/' .$update -> image;
            return apiResponse(201, 'success', 'berhasil ditambah',$update);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }

    //Edit educations
    public function update(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::transaction(function () use ($request, $id) {
                
                if ($request->has('image')) {
                    $oldImage = Education::where('id', $id)->first()->image;
    
                    if ($oldImage) {
                        $pleaseRemove = base_path('public/images/Register/') . $oldImage;
    
                        if (file_exists($pleaseRemove)) {
                            unlink($pleaseRemove);
                        }
                    }
    
                    $extension = $request->file('image')->getClientOriginalExtension();
    
                    $name = date('YmdHis') . '' . $id . '.' . $extension;
    
                    $path = base_path('public/images/Register');
    
                    $request->file('image')->move($path, $name);
    
                    Education::where('id', $id)->update([
                        'user_id' => auth()->user()->id,
                        'title' => $request->title,
                        'content' => $request->content,
                        'image' => $name,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            });
            $update = Education::where('id',$id)->first();
            $update -> image = asset('public/images/Register') . '/' .$update -> image;
            return apiResponse(202, 'success', 'user berhasil disunting',$update);
            
            // return apiResponse(202,'success', 'berhasil diedit');
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
}
