<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Rest;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Attendance::create([
            'user_id' => 1,
            'clock_in_time' => "08:00:00",
            'clock_out_time' => "17:00:00",
            'work_start_time' => "8:00:00",
            'work_end_time' => '17:00:00',
            'attendance_status' => '退勤済',
            'late_reason' => '',
            'approval_status' =>'approval',
            'date' => "2024-12-16"
        ]);

        Rest::create([
            'user_id' => 1,
            'attendance_id' => 1,
            'rest_start_time' => "12:00:00",
            'rest_end_time' => "13:00:00"
        ]);
        Rest::create([
            'user_id' => 1,
            'attendance_id' => 1,
            'rest_start_time' => "15:00:00",
            'rest_end_time' => "15:30:00"
        ]);

        Attendance::create([
            'user_id' => 1,
            'clock_in_time' => "08:00:00",
            'clock_out_time' => "17:00:00",
            'work_start_time' => "8:00:00",
            'work_end_time' => '17:00:00',
            'attendance_status' => '退勤済',
            'late_reason' => '',
            'approval_status' =>'approval',
            'date' => "2024-12-17"
        ]);
        Rest::create([
            'user_id' => 1,
            'attendance_id' => 2,
            'rest_start_time' => "12:00:00",
            'rest_end_time' => "13:00:00"
        ]);
        Rest::create([
            'user_id' => 1,
            'attendance_id' => 2,
            'rest_start_time' => "15:00:00",
            'rest_end_time' => "15:30:00"
        ]);

        Attendance::create([
            'user_id' => 1,
            'clock_in_time' => "08:00:00",
            'clock_out_time' => "17:00:00",
            'work_start_time' => "8:00:00",
            'work_end_time' => '17:00:00',
            'attendance_status' => '退勤済',
            'late_reason' => '',
            'approval_status' =>'approval',
            'date' => "2024-12-18"
        ]);
        Rest::create([
            'user_id' => 1,
            'attendance_id' => 3,
            'rest_start_time' => "12:00:00",
            'rest_end_time' => "13:00:00"
        ]);
        Rest::create([
            'user_id' => 1,
            'attendance_id' => 3,
            'rest_start_time' => "15:00:00",
            'rest_end_time' => "15:30:00"
        ]);
    }
}
