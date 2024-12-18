<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\ApprovalRequest;
use App\Http\Requests\ApplicationRequest;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function application(ApplicationRequest $request){

        $attendance = Attendance::find($request->attendance_id);

        if($attendance){
            $data = $request->all();
            $data['attendance_year'] = str_replace('年', '', $data['attendance_year']);
            $data['attendance_date'] = str_replace(['月','日'],  ['-', ''] , $data['attendance_date']);

            //休憩は複数回可能であるため、配列にし、json形式でapproval_requestsテーブルに保存する
            $rest_times = [];
            foreach ($data['rest_start_time'] as $index => $start){
                $rest_times[] = [
                    'rest_start_time' => $start,
                    'rest_end_time' => $data['rest_end_time'][$index]
                ];
            }

            ///ApprovalRequestテーブル(承認申請用)に申請情報を追加
            $approval_route_id = 1; ///回覧ルートが複数ある場合は1から変更できるようにしたい
            ApprovalRequest::createApprovalRequest($attendance->id, $approval_route_id, $data, $rest_times);
        }

        return redirect()->route('attendance_detail', ['id' => $request->attendance_id] );
    }

    
    public function application_list(Request $request){

        $user = Auth::user();
        $approval_status = $request->approval_status ?? 'pending';


        $approval_requests = ApprovalRequest::with('user', 'attendance')
                                            ->approvalFilter($approval_status)
                                            ->get();
                                            
        return view('approve', compact(['approval_requests', 'user', 'approval_status']));
    }
}
