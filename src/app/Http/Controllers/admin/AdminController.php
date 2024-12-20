<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\ApprovalRequest;
use App\Models\Rest;
use App\Models\User;
use App\Http\Requests\AdminAttendanceRequest;
use Illuminate\Support\Facades\Storage;

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

    public function admin_attendance_update(AdminAttendanceRequest $request){
        $attendance_id = $request->attendance_id;
        $attendance = Attendance::find($attendance_id);
        $user_id = User::find($attendance->user_id)->value('id');
        if($attendance){
            $data = $request->all();
            $data['attendance_year'] = str_replace('年', '', $data['attendance_year']);
            $data['attendance_date'] = str_replace(['月', '日'], ['-',''] , $data['attendance_date']);

            //休憩は複数回可能であるため、配列にする。
            $rest_times = [];
            foreach ($data['rest_start_time'] as $index => $start){
                $rest_times[] = [
                    'rest_start_time' => $start,
                    'rest_end_time' => $data['rest_end_time'][$index]
                ];
            }

            //管理者が直接スタッフの勤怠情報を更新 (Attendancesテーブル更新)
            Attendance::adminAttendanceUpdate($attendance_id, $data);

            //休憩時間テーブルを更新
            Rest::adminRestUpdate($attendance_id, $rest_times, $user_id);

            return redirect('/admin/attendance/' . $attendance_id);
        }


    }

    public function admin_staff_list(){
        $users = User::all();
        return view('admin.admin_staff_list', compact('users'));
    }

    public function attendance_export(Request $request){
        //該当する月の勤怠管理情報を取得するため、検索に必要な月の値を取得
        $searchDate = new Carbon($request->formattedMonth);
        if($request->num == 0){
            $month = $searchDate->format('Y-m');
        } elseif($request->num > 0){
            $month = $searchDate->addMonth($request->num)->format('Y-m');
        } else {
            $month = $searchDate->subMonth(-$request->num)->format('Y-m');
        }
        $searchDate = $searchDate->format('Y-m');

        $user_name = User::find($request->user_id)->value('name');

        $attendances = Attendance::where('user_id' , $request->user_id)
                                  ->where('date' , 'like' , $month . '%')
                                  ->get();

        $adjustedAttendances = Attendance::adjustAttendance($attendances);

        $attendanceFileName = $searchDate . '_' . $user_name . '_attendance_export.csv';
        $filePath = storage_path('app/csv/' . $attendanceFileName);
        $handle = fopen($filePath , 'w');
        fputcsv($handle, ['日付', '出勤', '退勤' ,'休憩' , '合計']); //ヘッダー行

        foreach ($adjustedAttendances as $attendance){
            fputcsv($handle, [$attendance->date, $attendance->clock_in_time, $attendance->clock_out_time, $attendance->rest_sum, $attendance->work_time]);
        }

        fclose($handle);

        $headers = [
            'Content-Type' => 'text/csv'
        ];

        return response()->download($filePath, $attendanceFileName, $headers);
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

        $attendances = Attendance::where('user_id', $user->id)
                                 ->where('date', 'like', "%$month%")
                                 ->get();

        $adjustedAttendances = Attendance::adjustAttendance($attendances);
        $formattedMonth = $datetime->format('Y/m');

        return view('admin.admin_staff_attendance_list', compact(['attendances', 'month', 'num', 'formattedMonth', 'user']));
    }

    public function admin_application_list(Request $request){ 

        $approval_status = $request->approval_status ?? 'pending';


        $approval_requests = ApprovalRequest::with('user', 'attendance')
                                            ->approvalFilter($approval_status)
                                            ->get();
        
        foreach  ($approval_requests as $approval_request){
            $attendance_user_id = Attendance::find($approval_request->attendance_id)->value('user_id');
            $approval_request->user_name = User::find($attendance_user_id)->value('name');
        }
                                            
        return view('admin.approve', compact(['approval_requests', 'approval_status']));
    }
    
    public function admin_application_detail($id){
        $approval_request = ApprovalRequest::with(['attendance'])->findOrFail($id);
        $user = User::find($approval_request->attendance->user_id);
        $rests = Rest::where('attendance_id' ,$approval_request->attendance_id)->get();

        return view('admin.approve_correct', compact(['approval_request','user','rests']));
    }

    public function admin_application_approve(Request $request){
        
        $approval_request = ApprovalRequest::with(['user','attendance'])->findOrFail($request->approval_request_id);
        //申請内容が問題ない場合、申請内容をattendanceテーブルに反映
        Attendance::approvalAttendance($approval_request);

        //申請内容が問題ない場合、申請内容をrestsテーブルに反映
        Rest::approvalRest($approval_request);

        //申請内容に問題がなければapproval_requestsのapproval_statusをapprovalに変更
        ApprovalRequest::approvalApplication($approval_request);
    
        return redirect('/admin/stamp_correction_request/approve/' . $approval_request->id );
    }
}