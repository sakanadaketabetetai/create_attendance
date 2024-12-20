<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Attendance;
use App\Models\Rest;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserAttendanceUpdateTest extends TestCase
{
    use RefreshDatabase;
    private $user1;
    private $user2;
    private $user3;
    private $user4;
    private $admin;
    private $attendance1;
    private $attendance2;
    private $attendance3;
    private $attendance4;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function setUp():void
    {
        parent::setUp();

        $this->user1 = User::create([
            'name' => 'テストユーザー1',
            'email' => 'user1@example.com',
            'password' => Hash::make('user12345'),
            'email_verified_at' => Carbon::now()
        ]);
        $this->attendance1 = Attendance::create([
            'user_id' => $this->user1->id,
            'clock_in_time' => "08:00:00",
            'clock_out_time' => "17:00:00",
            'work_start_time' => "8:00:00",
            'work_end_time' => '17:00:00',
            'attendance_status' => '退勤済',
            'late_reason' => '',
            'approval_status' =>'approval',
            'date' => "2024-12-16"
        ]);

        $this->user2 = User::create([
            'name' => 'テストユーザー2',
            'email' => 'user2@example.com',
            'password' => Hash::make('user23456'),
            'email_verified_at' => Carbon::now()
        ]);
        $this->attendance2 = Attendance::create([
            'user_id' => $this->user2->id,
            'clock_in_time' => "08:00:00",
            'clock_out_time' => "17:00:00",
            'work_start_time' => "8:00:00",
            'work_end_time' => '17:00:00',
            'attendance_status' => '退勤済',
            'late_reason' => '',
            'approval_status' =>'approval',
            'date' => "2024-12-16"
        ]);

        $this->user3 = User::create([
            'name' => 'テストユーザー3',
            'email' => 'user3@example.com',
            'password' => Hash::make('user34567'),
            'email_verified_at' => Carbon::now()
        ]);
        $this->attendance3 = Attendance::create([
            'user_id' => $this->user3->id,
            'clock_in_time' => "08:00:00",
            'clock_out_time' => "17:00:00",
            'work_start_time' => "8:00:00",
            'work_end_time' => '17:00:00',
            'attendance_status' => '退勤済',
            'late_reason' => '',
            'approval_status' =>'approval',
            'date' => "2024-12-16"
        ]);

        $this->user4 = User::create([
            'name' => 'テストユーザー4',
            'email' => 'user4@example.com',
            'password' => Hash::make('user45678'),
            'email_verified_at' => Carbon::now()
        ]);
        $this->attendance4 = Attendance::create([
            'user_id' => $this->user4->id,
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
            'user_id' => $this->user1->id,
            'attendance_id' => $this->attendance1->id,
            'rest_start_time' => '12:00:00',
            'rest_end_time' => '13:00:00',
        ]);
        Rest::create([
            'user_id' => $this->user2->id,
            'attendance_id' => $this->attendance2->id,
            'rest_start_time' => '12:00:00',
            'rest_end_time' => '13:00:00',
        ]);
        Rest::create([
            'user_id' => $this->user3->id,
            'attendance_id' => $this->attendance3->id,
            'rest_start_time' => '12:00:00',
            'rest_end_time' => '13:00:00',
        ]);
        Rest::create([
            'user_id' => $this->user4->id,
            'attendance_id' => $this->attendance4->id,
            'rest_start_time' => '12:00:00',
            'rest_end_time' => '13:00:00',
        ]);

        $this->admin = Admin::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin12345'),
            'email_verified_at' => Carbon::now()
        ]);
        
        $this->withoutMiddleware();
    }

    /** @test
     *  @group user_attendance_update
     */
    public function user_attendance_clock_in_time_validate(){
        $response = $this->post('/login',[
            'email' => $this->user1->email,
            'password' => 'user12345'
        ]); 
        $response = $this->get('/attendance');
        $this->assertAuthenticated();
        $num = 0;
        $response = $this->get('/attendance/list' . $num);
        $response = $this->get('/attendance/{{ $this->attendance1->id }}');
        $response = $this->post('/attendance/stamp_correction_request', [
            'attendance_id' => $this->attendance1->id,
            'clock_in_time' => '18:00:00',
            'clock_out_time' => '17:00:00',
            'rest_start_time' => ['12:00:00'],
            'rest_end_time' => ['13:00:00'],
            'late_reason' => 'test reason'
        ]);

        $response->assertSessionHasErrors([
            'clock_in_time' => '出勤時間もしくは退勤時間が不適切な値です。'
        ]);
    }

    /** @test
     *  @group user_attendance_update
     */
    public function user_attendance_rest_start_time_validate(){
        $response = $this->post('/login',[
            'email' => $this->user1->email,
            'password' => 'user12345'
        ]);
        $response = $this->get('/attendance');
        $this->assertAuthenticated();

        $num = 0;
        $response = $this->get('/attendance/list' . $num);
        $response = $this->get('/attendance/' . $this->attendance1->id);
        $response = $this->post('/attendance/stamp_correction_request', [
            'attendance_id' => $this->attendance1->id,
            'attendance_year' => Carbon::yesterday()->format('Y年'),
            'attendance_date' => Carbon::yesterday()->format('m月d日'),
            'clock_in_time' => '08:00:00',
            'clock_out_time' => '17:00:00',
            'rest_start_time' => ['18:00:00'],
            'rest_end_time' => ['15:00:00'],
            'late_reason' => 'test reason'
        ]);

        $response->assertSessionHasErrors([
            'rest_start_time.0' => '休憩時間が勤務時間外です。'
        ]);
    }

    /** @test
     *  @group user_attendance_update
     */
    public function user_attendance_rest_end_time_validate(){
        $response = $this->post('/login',[
            'email' => $this->user2->email,
            'password' => 'user23456'
        ]);
        $response = $this->get('/attendance');
        $this->assertAuthenticated();

        $num = 0;
        $response = $this->get('/attendance/list' . $num);
        $response = $this->get('/attendance/' . $this->attendance2->id);
        $response = $this->post('/attendance/stamp_correction_request', [
            'attendance_id' => $this->attendance2->id,
            'attendance_year' => Carbon::yesterday()->format('Y年'),
            'attendance_date' => Carbon::yesterday()->format('m月d日'),
            'clock_in_time' => '08:00:00',
            'clock_out_time' => '17:00:00',
            'rest_start_time' => ['12:00:00'],
            'rest_end_time' => ['18:00:00'],
            'late_reason' => 'test reason'
        ]);

        $response->assertSessionHasErrors([
            'rest_end_time.0' => '休憩時間が勤務時間外です。'
        ]);
    }

    /** @test
     *  @group user_attendance_update
     */
    public function user_attendance_late_reason_validate(){
        $response = $this->post('/login',[
            'email' => $this->user3->email,
            'password' => 'user34567'
        ]);
        $response = $this->get('/attendance');
        $this->assertAuthenticated();

        $num = 0;
        $response = $this->get('/attendance/list' . $num);
        $response = $this->get('/attendance/' . $this->attendance3->id);
        $response = $this->post('/attendance/stamp_correction_request', [
            'attendance_id' => $this->attendance3->id,
            'attendance_year' => Carbon::yesterday()->format('Y年'),
            'attendance_date' => Carbon::yesterday()->format('m月d日'),
            'clock_in_time' => '08:00:00',
            'clock_out_time' => '17:00:00',
            'rest_start_time' => ['12:00:00'],
            'rest_end_time' => ['13:00:00'],
            'late_reason' => ''
        ]);

        $response->assertSessionHasErrors([
            'late_reason' => '備考を記入してください'
        ]);
    }

    /** @test
     *  @group user_attendance_update
     */
    public function user_attendance_update(){
        $response = $this->post('/login',[
            'email' => $this->user4->email,
            'password' => 'user45678'
        ]);
        $response = $this->get('/attendance');
        $this->assertAuthenticated();
        $num = 0;
        $response = $this->get('/attendance/list/' . $num);
        $response = $this->get('/attendance/' . $this->attendance4->id);
        $response = $this->post('/attendance/stamp_correction_request', [
            'attendance_id' => $this->attendance4->id,
            'attendance_year' => Carbon::yesterday()->format('Y年'),
            'attendance_date' => Carbon::yesterday()->format('m月d日'),
            'clock_in_time' => '08:30:00',
            'clock_out_time' => '17:30:00',
            'rest_start_time' => ['12:30:00'],
            'rest_end_time' => ['13:30:00'],
            'late_reason' => 'test reason'
        ]);

        $this->assertDatabaseHas('approval_requests', [
            'attendance_id' => $this->attendance4->id,
            'approval_route_id' => 1,
            'admin_id' => $this->admin->id,
            'clock_in_time' => '08:30:00',
            'clock_out_time' => '17:30:00',
            'late_reason' => 'test reason',
        ]);
    }
}
