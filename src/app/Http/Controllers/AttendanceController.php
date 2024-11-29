<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\ApprovalRequest;
use App\Models\Rest;

class AttendanceController extends Controller
{
    public function attendance(){

        $attendance = Attendance::getAttendance();

        //当日の日付を取得し、フォーマットを変更
        $datetime = Carbon::now();
        $formattedDate = $datetime->format('Y年m月d日');
        $weekMap = ['日','月','火', '水','木', '金', '土'];
        $dayOfWeek = $weekMap[$datetime->dayOfWeek];
        $date = $formattedDate. '(' . $dayOfWeek . ')';

        //現在の時刻を取得し、フォーマット変更
        $time = $datetime->format('H:i');

        //当日の勤務実績がない場合、当日に勤務外を表示する
        if(empty($attendance)){
            $attendance_status = "勤務外";
            return view('attendance.attendance', compact(['date', 'time', 'attendance_status']));
        }

        $rest = $attendance->rests->whereNull('rest_end_time')->first();
        $attendance_status = $attendance->attendance_status;

        return view('attendance.attendance', compact(['date', 'time', 'attendance_status']));
    }

    public function attendance_clock_in(){

        $user_id = Auth::id();

        $datetime = new Carbon();
        $date = $datetime->toDateString();
        $time = $datetime->toTimeString();

        Attendance::create([
            'user_id' => $user_id,
            'clock_in_time' => $time,
            'work_start_time' => "08:00:00",
            'work_end_time' => "17:00:00",
            'attendance_status' => '出勤中',
            'date' => $date
        ]);

        return redirect('/attendance');
    }

    public function attendance_clock_out(){
        $user_id = Auth::id();

        $datetime = new Carbon();

        $date = $datetime->toDateString();
        $time = $datetime->toTimeString();

        Attendance::where('user_id' , $user_id)->where('date', $date)->update([
            'clock_out_time' => $time,
            'attendance_status' => '退勤済'
        ]);

        return redirect('/attendance');
    }

    public function attendance_index(Request $request){
        $user_id = Auth::id();

        $num = (int)$request->num;
        $datetime = new Carbon();

        if($num == 0){
            $month = $datetime->format('Y-m');
        } elseif ($num > 0){
            $month = $datetime->addMonth($num)->format('Y-m');
        } else {
            $month = $datetime->subMonth(-$num)->format('Y-m');
        }

        $attendances = Attendance::where('user_id', $user_id)->where('date', 'like', "%$month%")->get();

        $adjustedAttendances = Attendance::adjustAttendance($attendances);

        return view('attendance.attendance_index', compact(['attendances', 'month', 'num']));
    }

    public function attendance_detail($id){
        $user = Auth::user();
        $attendance = Attendance::find($id);
        $rests = Rest::where('attendance_id', $attendance->id)->get();

        $carbonDate = new Carbon($attendance->date);

        $attendance->formattedYear = $carbonDate->format('Y年');
        $attendance->formattedDate = $carbonDate->format('m月d日');

        $approval_request = ApprovalRequest::where('attendance_id', $attendance->id)
                                            ->where('approver_status', 'pending')
                                            ->first();
        
        if($approval_request){
            $attendance->approval_status = 'pending';
        }

        return view('attendance.attendance_detail', compact(['attendance', 'rests', 'user']));
    }
}
