<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'clock_in_time', 'clock_out_time', 'work_start_time',
        'work_end_time',
        'attendance_status', 'late_reason', 'approval_status', 'date'
    ];

    protected function rests(){
        return $this->hasMany(Rest::class);
    }
    protected function approval_requests(){
        return $this->hasMany(ApprovalRequest::class);
    }
    protected function users(){
        return $this->belongsTo(User::class);
    }

    public static function getAttendance(){
        $user_id = Auth::id();

        $datetime = new Carbon;
        $date = $datetime->toDateString();

        $attendance = Attendance::where('user_id', $user_id)->where('date', $date)->first();

        return $attendance;
    }

    public static function attendanceClockIn($user_id, $date, $time)
    {
        Attendance::create([
            'user_id' => $user_id,
            'clock_in_time' => $time,
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'attendance_status'=> '出勤中',
            'date' => $date
        ]);
    }

    public static function attendanceClockOut($user_id, $date, $time)
    {
        Attendance::where('user_id', $user_id)
                  ->where('date', $date)
                  ->update([
                    'clock_out_time' => $time,
                    'attendance_status' => '退勤済'
                  ]);
    }

    public static function approvalAttendance($approval_request){
        Attendance::where('id', $approval_request->attendance_id)->update([
            'clock_in_time' => $approval_request->clock_in_time,
            'clock_out_time' => $approval_request->clock_out_time,
            'date' => $approval_request->date,
            'late_reason' => $approval_request->late_reason,
            'approval_status' => 'approval'
        ]);
    }

    public static function adminAttendanceUpdate($attendance_id, $data){
        $attendance_date = $data['attendance_year']. '-' . $data['attendance_date'];

        Attendance::where('id', $attendance_id)->update([
            'clock_in_time' => $data['clock_in_time'],
            'clock_out_time' => $data['clock_out_time'],
            'date' => $attendance_date,
            'late_reason' => $data['late_reason'],
            'approval_status' => 'approval'
        ]);

    }

    public static function adjustAttendance($attendances){
        foreach ($attendances as $index => $attendance){
            $rests = $attendance->rests;
            $sum = 0;
            foreach($rests as $rest){
                $start_time = $rest->rest_start_time;
                $start_datetime = new Carbon($start_time);

                $end_time = $rest->rest_end_time;
                $end_datetime = new Carbon($end_time);

                $diff_seconds = $start_datetime->diffInSeconds($end_datetime);
                $sum = $sum + $diff_seconds;
            }

            $start_at = new Carbon($attendance->clock_in_time);
            $end_at = new Carbon($attendance->clock_out_time);

            $diff_start_end = $start_at->diffInSeconds($end_at);
            $diff_work = $diff_start_end - $sum;

            $rest_hours = floor($sum/3600);
            $rest_minutes = floor(($sum / 60) % 60);
            $rest_seconds = $sum % 60;

            $work_hours = floor($diff_work / 3600);
            $work_minutes = floor(($diff_work / 60) % 60);
            $work_seconds = $diff_work % 60;

            $rest_time = Carbon::createFromTime($rest_hours, $rest_minutes, $rest_seconds);
            $work_time = Carbon::createFromTime($work_hours, $work_minutes, $work_seconds);

            $attendance->rest_sum = $rest_time->toTimeString();
            $attendance->work_time = $work_time->toTimeString();
        }

        return $attendances;
    }

}