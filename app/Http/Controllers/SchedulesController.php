<?php

namespace App\Http\Controllers;

use Exception;
use App\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchedulesController extends Controller
{
    public function index(){
        $schedule = Schedule::get();
        return apiResponse('200', 'success','list:',$schedule);
    }

    public function store(Request $request){
        try {
            $schedule = Schedule::create([
                'user_id' => auth()->user()->id,
                'day' => date('Y-m-d'),
                'status' => $request->status,
            ]); 
            return apiResponse(200, 'success','list :', $schedule);
        }
        
        catch(Exception $e) {
            return apiResponse(400, 'error', $e);
        }
        
    }

    public function update(Request $request, $id){
        try { 
            DB::transaction(function ()use($request,$id) {
                Schedule::where('id',$id)->update([
                    'user_id' => auth()->user()->id,
                    'day' => date('Y-m-d'),
                    'status' => $request->status,
                ]); 
            });
            return apiResponse(200,'success','berhasil  diedit',$id);
            }catch  (Exception $e) {
                return apiResponse(200,'error','error',$e);
            }
    }

    public function destroy($id){
        try {
            DB::transaction(function ()use($id) {
                Schedule::where('id',$id)->delete();
            });
            return apiResponse(200,'success','Berhasil dihapus:');
        }catch (Exception $e) {
            return apiResponse(200,'error','error',$e);
        }
    }


}
