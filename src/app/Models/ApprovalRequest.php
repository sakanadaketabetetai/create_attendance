<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ApprovalRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'attendance_id', 
        'approval_route_id', 
        'approval_status', 
        'admin_id', 
        'clock_in_time',
        'clock_out_time',
        'rest_times',
        'date',
        'late_reason',
        'approval_at'
    ];

    public function attendance(){
        return $this->belongsTo(Attendance::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function approval_route(){
        return $this->belongsTo(ApprovalRoute::class);
    }

    //一般ユーザーの勤怠情報変更申請処理
    public static function createApprovalRequest($attendance_id, $approval_route_id, $data, $rest_times)
    {
        $attendance_date = $data['attendance_year'] . '-' . $data['attendance_date'];

        $approval_admin_ids = ApprovalRouteUser::where('approval_route_id', $approval_route_id)->pluck('admin_id');

        foreach ($approval_admin_ids as $admin_id){
            self::create([
                'attendance_id' => $attendance_id,
                'approval_route_id' => $approval_route_id,
                'admin_id' => $admin_id,
                'date' => Carbon::parse($attendance_date),
                'clock_in_time' => $data['clock_in_time'],
                'clock_out_time' => $data['clock_out_time'],
                'rest_times' => json_encode($rest_times), 
                'late_reason' => $data['late_reason'],
                'approval_status' => 'pending',
                'approval_at' => Carbon::now()
            ]);
        }
    }

    public static function approvalApplication($approval_request){
        ApprovalRequest::where('id', $approval_request->id)->update([
            'approval_status' => 'approval'
        ]);
    }

    //application_listで使用
    public function scopeApprovalFilter($query, $approval_status){
        return $query->where('approval_status', $approval_status);
    }
}
