<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_attendance = Attendance::create([
            'user_id' => 1,
            'clock_in_time' => "08:00:00",
            'clock_out_time' => "17:00:00",
            'work_start_time' => "8:00:00",
            'work_end_time' => '17:00:00',
            'attendance_status' => '退勤済',
            'late_reason' => '',
            'approval_status' =>'approval',
            'date' => "2024-11-18"
        ]);

        $user_attendance = Attendance::create([
            'user_id' => 1,
            'clock_in_time' => "08:00:00",
            'clock_out_time' => "17:00:00",
            'work_start_time' => "8:00:00",
            'work_end_time' => '17:00:00',
            'attendance_status' => '退勤済',
            'late_reason' => '',
            'approval_status' =>'approval',
            'date' => "2024-11-19"
        ]);
    }
}
