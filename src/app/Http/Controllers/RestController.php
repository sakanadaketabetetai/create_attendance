<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Rest;

class RestController extends Controller
{
    public function rest_start(){

        $attendance = Attendance::getAttendance();

        $user_id = Auth::id();
        
        $datetime = new Carbon();
        $time = $datetime->toTimeString();

        Rest::create([
            'user_id' => $user_id,
            'attendance_id' => $attendance->id,
            'rest_start_time' => $time,
        ]);

        //勤務状態を変更
        $attendance->attendance_status = '休憩中';
        $attendance->save();

        return redirect('/attendance');
    }

    public function rest_end(){

        $attendance = Attendance::getAttendance();

        $user_id = Auth::id();
        $datetime = new Carbon();
        $time = $datetime->toTimeString();
        $rest = Rest::where('user_id', $user_id)
                    ->where('attendance_id', $attendance->id)
                    ->whereNull('rest_end_time')->first();

        $rest->rest_end_time = $time;
        $rest->save();

        $attendance->attendance_status = '出勤中';
        $attendance->save();

        return redirect('/attendance');
    }
}
