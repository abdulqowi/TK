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
                 $id = Schedule::insertGetId([
                    'created_at' => date('Y-m-d H:i:s'),
                    'user_id' => request('user_id'),
                    'day' => $master->id, 
                    'status' => request('status'),
                ]);

                $data = Schedule::where('schedules.id', $id)
                ->join('masters', 'masters.id', '=', 'schedules.day')
                ->select([
                    'schedules.*', 'masters.day as dayName'
                ])
                ->first();
            return apiResponse(200, 'success','list :', $data);
        }
        catch(Exception $e) {
            dd($e);
            return apiResponse(400, 'error', $e);
        }
        
    }

    public function update(Request $request,$id){
        try { 
        Schedule::where('id',$id)->update([
                    'user_id' => auth()->user()->id,
                    'status' => $request->status,
                ]); 
                $data = Schedule::where('id',$id)->first();
            return apiResponse(200,'success','berhasil  diedit',$data);
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
