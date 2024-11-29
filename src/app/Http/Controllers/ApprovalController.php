<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\ApprovalRequest;
use App\Models\ApprovalRouteUser;
use Carbon\Carbon;

class ApprovalController extends Controller
{
    public function application(Request $request){

        $attendance_date = $request->formattedYear . $request->formattedDate;

        //申請情報を該当する勤怠管理情報へアップデート　申請状況はpendingとする
        Attendance::find($request->attendance_id)->update([
            'date' => Carbon::parse($attendance_date),
            'clock_in_time' => $request->clock_in_time,
            'clock_out_time' => $request->clock_out_time,
            'rest_start_time' => $request->rest_start_time,
            'rest_end_time' => $request->rest_end_time,
            'late_reason' => $request->late_reason,
            'approval_status' => 'pending'
        ]);
                    
        $attendance = Attendance::find($request->attendance_id);

        //回覧ルートから承認者を取得し、approval_requestsテーブルに承認情報を挿入　承認者は複数でも対応可
        $approval_user_ids = ApprovalRouteUser::where('approval_route_id', 1)->pluck('user_id');
        foreach ($approval_user_ids as $user_id){
            $approval_request = ApprovalRequest::create([
                'attendance_id' => $request->attendance_id,
                'approval_route_id' => 1,
                'user_id' => $user_id, //承認者
                'approver_status' => 'pending',
            ]);
        }

        return redirect()->route('attendance_detail', ['id' => $request->attendance_id] );
    }

    public function application_list(Request $request){
        
    }
}
