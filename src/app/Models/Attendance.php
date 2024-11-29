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