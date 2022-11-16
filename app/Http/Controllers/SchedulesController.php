<?php

namespace App\Http\Controllers;

use App\Master;
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
        $master = Master::where('day', $request->day)->first();
        try {
                 Schedule::create([
                    'user_id' => request('user_id'),
                    'day' => $master->id, 
                    'status' => request('status'),
                ]); 
            
            return apiResponse(200, 'success','list :', $master);
        }
        catch(Exception $e) {
            // dd($e);
            return apiResponse(400, 'error', $e);
        }
        
    }

    public function update(Request $request,$id){
        try { 
        $schedule = Schedule::where('id',$id)->update([
                    'user_id' => auth()->user()->id,
                    'status' => $request->status,
                ]); 
            return apiResponse(200,'success','berhasil  diedit',$schedule);
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
