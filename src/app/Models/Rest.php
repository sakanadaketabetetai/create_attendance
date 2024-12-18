<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','attendance_id', 'rest_start_time', 'rest_end_time'
    ];

    public static function approvalRest($approval_request)
    {
        Rest::where('attendance_id', $approval_request->attendance_id)->delete();
        $rest_times = json_decode($approval_request->rest_times, true);
        foreach($rest_times as $rest_time){
            Rest::create([
                'attendance_id'=> $approval_request->attendance_id,
                'user_id' => User::find($approval_request->attendance->user_id)->value('id'),
                'rest_start_time' => $rest_time['rest_start_time'],
                'rest_end_time' => $rest_time['rest_end_time']
            ]);
        }
    }

    public static function adminRestUpdate($attendance_id, $rest_times, $user_id){
        Rest::where('attendance_id' , $attendance_id)->delete();
        foreach($rest_times as $rest_time){
            Rest::create([
                'attendance_id' => $attendance_id,
                'user_id' => $user_id,
                'rest_start_time' => $rest_time['rest_start_time'],
                'rest_end_time' => $rest_time['rest_end_time']
            ]);
        }
        
    }
}
