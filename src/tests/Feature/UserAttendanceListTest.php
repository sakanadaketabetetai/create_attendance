<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Rest;
use App\Models\Attendance;
use Tests\TestCase;

class UserAttendanceListTest extends TestCase
{
    use RefreshDatabase;
    private $user1;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user1 = User::create([
            'name' => '勤務外ユーザー',
            'email' => 'user1@example.com',
            'password' => Hash::make('user112345'),
            'email_verified_at' => Carbon::now()
        ]);
        $attendance1 = Attendance::create([
            'user_id' => $this->user1->id,
            'clock_in_time' => '08:00:00',
            'clock_out_time' => '17:00:00',
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' => Carbon::yesterday()->toDateString(),
            'attendance_status' => '退勤済',
        ]);
        $attendance2 = Attendance::create([
            'user_id' => $this->user1->id,
            'clock_in_time' => '08:00:00',
            'clock_out_time' => '17:00:00',
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' => Carbon::now()->toDateString(),
            'attendance_status' => '退勤済',
        ]);

        Rest::create([
            'user_id' => $this->user1->id,
            'attendance_id' => $attendance1->id,
            'rest_start_time' => '12:00:00',
            'rest_end_time' => '13:00:00',
        ]);
        Rest::create([
            'user_id' => $this->user1->id,
            'attendance_id' => $attendance2->id,
            'rest_start_time' => '12:00:00',
            'rest_end_time' => '13:00:00',
        ]);

        $this->withoutMiddleware();
    }

    /** @test
     *  @group user_attendance_list
     */
    public function user_attendance_list(){
        $response = $this->post('/login' ,[
            'email' => $this->user1->email,
            'password' => 'user112345'
        ]);
        $response->assertRedirect('/attendance');
        $this->assertAuthenticated();

        $response = $this->get('/attendance/list/0');
        $attendances = Attendance::where('user_id', $this->user1->id)->get();
        $adjustAttendances = Attendance::adjustAttendance($attendances);

        foreach ($adjustAttendances as $attendance){
            $datetime = new Carbon($attendance->date);
            $formattedDate = $datetime->format('m/d');
            $weekMap = ['日','月','火', '水','木', '金', '土'];
            $dayOfWeek = $weekMap[$datetime->dayOfWeek];
            $date = $formattedDate. '(' . $dayOfWeek . ')';
            $response->assertSee($date);
            $response->assertSee($attendance->clock_in_time);
            $response->assertSee($attendance->clock_out_time);
            $response->assertSee($attendance->rest_sum);
            $response->assertSee($attendance->work_time);
        }
        $response = $this->post('/logout');
    }

    /** @test
     *  @group user_attendance_list
     */
    public function user_attendance_list_this_month(){
        $response = $this->post('/login', [
            'email' => $this->user1->email,
            'password' => 'user112345'
        ]);

        $response->assertRedirect('/attendance');
        $this->assertAuthenticated();
        $num = 0;
        $response = $this->get('/attendance/list/' . $num);
        $month = Carbon::now()->format('Y/m');
        $response->assertSee($month);
    }

    /** @test
     *  @group user_attendance_list
     */
    public function user_attendance_list_last_month(){
        $response = $this->post('/login', [
            'email' => $this->user1->email,
            'password' => 'user112345'
        ]);

        $response->assertRedirect('/attendance');
        $this->assertAuthenticated();
        $num = -1;
        $response = $this->get('/attendance/list/' . $num);
        $date = Carbon::now();
        $month = $date->subMonth(-$num)->format('Y/m');
        $response->assertSee($month);
    }
}
