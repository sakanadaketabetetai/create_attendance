<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Rest;
use App\Models\User;

class AdminController extends Controller
{
    public function admin_attendance_list(Request $request){
        $num = (int)$request->num;
        $datetime = new Carbon();
        if($num == 0){
            $date = $datetime;
        } else {
            $date = $datetime->subDay(-$num);
        }

        $searchDate = $date->format('Y-m-d');

        $attendances = Attendance::where('date', $searchDate)->get();
        foreach ($attendances as $attendance){
            $attendance->user_name = User::find($attendance->user_id)->value('name');
        }

        $adjustedAttendances = Attendance::adjustAttendance($attendances);

        return view('admin.admin_attendance_list', compact(['attendances', 'date', 'num']));
    }

    public function admin_attendance_detail($id){
        $attendance = Attendance::find($id);
        $user = User::find($attendance->user_id);
        $rests = Rest::where('attendance_id', $attendance->id)->get();

        $carbonDate = new Carbon($attendance->date);
        
        $attendance->formattedYear = $carbonDate->format('Y年');
        $attendance->formattedDate = $carbonDate->format('m月d日');

        return view('admin.admin_attendance_detail', compact(['attendance', 'rests', 'user']));
    }

    public function admin_staff_list(){
        $users = User::all();
        return view('admin.admin_staff_list', compact('users'));
    }

    public function admin_staff_attendance_list($id, $num){
        $user = User::find($id);

        $num = (int)$num;
        $datetime = new Carbon();

        if($num == 0){
            $month = $datetime->format('Y-m');
        } elseif($num > 0){
            $month = $datetime->addMonth($num)->format('Y-m');
        } else {
            $month = $datetime->subMonth(-$num)->format('Y-m');
        }

        $attendances = Attendance::where('user_id', $user->id)->where('date', 'like', "%$month%")->get();

        $adjustedAttendances = Attendance::adjustAttendance($attendances);
        $formattedMonth = $datetime->format('Y/m');

        return view('admin.admin_staff_attendance_list', compact(['attendances', 'month', 'num', 'formattedMonth', 'user']));
        

    }
}
