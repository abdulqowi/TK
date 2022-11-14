<?php

namespace App\Http\Controllers;

use Exception;
use App\Master;
use Illuminate\Http\Request;


class MasterController extends Controller
{
    public function index(){
        $master = Master::get();
        return apiResponse('200', 'success','list:',$master);
    }

    public function show($id){
        $id = Master::where('id', $id)->first();
        return apiResponse(200,'success','list',$id);
    }

    public function store(Request $request){
        try {
            $master = Master::create([
                'price' => $request->price,
                'day' => date('Y-m-d'),
            ]); 
            return apiResponse(200, 'success','list :', $master);
        }
        
        catch(Exception $e) {
            return apiResponse(400, 'error', $e);
        }
        
    }
}
