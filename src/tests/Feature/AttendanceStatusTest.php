<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use Tests\TestCase;

class AttendanceStatusTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

     public function setUp() :void
     {
        parent::setUp();

        //勤務外のユーザー
        $user1 = User::create([
            'name' => '勤務外ユーザー',
            'email' => 'user1@example.com',
            'password' => Hash::make('user112345'),
            'email_verified_at' => Carbon::now()
        ]);
        Attendance::create([
            'user_id' => $user1->id,
            'clock_in_time' => '08:00:00',
            'clock_out_time' => '17:00:00',
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' => Carbon::yesterday(),
            'attendance_status' => '退勤済',
        ]);
        //勤務中のユーザー
        $user2 = User::create([
            'name' => '勤務中ユーザー',
            'email' => 'user2@example.com',
            'password' => Hash::make('user212345'),
            'email_verified_at' => Carbon::now()
        ]);
        Attendance::create([
            'user_id' => $user2->id,
            'clock_in_time' => '08:00:00',
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' => new Carbon('08:00:00'),
            'attendance_status' => '出勤中',
        ]);

        //休憩中のユーザー
        $user3 = User::create([
            'name' => '休憩中ユーザー',
            'email' => 'user3@example.com',
            'password' => Hash::make('user312345'),
            'email_verified_at' => Carbon::now()
        ]);
        Attendance::create([
            'user_id' => $user3->id,
            'clock_in_time' => '08:00:00',
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' => new Carbon('08:00:00'),
            'attendance_status' => '休憩中',
        ]);

        //退勤済のユーザー
        $user4 = User::create([
            'name' => '退勤済ユーザー',
            'email' => 'user4@example.com',
            'password' => Hash::make('user412345'),
            'email_verified_at' => Carbon::now()
        ]);
        Attendance::create([
            'user_id' => $user4->id,
            'clock_in_time' => '08:00:00',
            'clock_out_time' => '17:00:00',
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' => new Carbon('08:00:00'),
            'attendance_status' => '退勤済',
        ]);

        $this->withoutMiddleware(); //ミドルウェアを一時的に無効化
     }

     /** @test
      *  @group attendance_status
      */
     public function attendance_status_outside_working_hours(){
        $response = $this->get('/login');
        $token = csrf_token();

        $response = $this->post('/login', [
            'email' => 'user1@example.com',
            'password' => 'user112345',
            '_token' => $token
        ]);

        $response->assertRedirect('/attendance');
        $this->assertAuthenticated();
        
        $response = $this->get('/attendance');

        $attendance_status = "勤務外";
        $response ->assertSee($attendance_status);
     }
     /** @test
      *  @group attendance_status
      */
     public function attendance_status_clock_in(){
        $response = $this->get('/login');
        $token = csrf_token();

        $response = $this->post('/login', [
            'email' => 'user2@example.com',
            'password' => 'user212345',
            '_token' => $token
        ]);

        $response->assertRedirect('/attendance');
        $this->assertAuthenticated();

        $response = $this->get('/attendance');

        $attendance_status = "出勤中";
        $response ->assertSee($attendance_status);
     }
     /** @test
      *  @group attendance_status
      */
     public function attendance_status_rest_start(){
        $response = $this->get('/login');
        $token = csrf_token();

        $response = $this->post('/login', [
            'email' => 'user3@example.com',
            'password' => 'user312345',
            '_token' => $token
        ]);

        $response->assertRedirect('/attendance');
        $this->assertAuthenticated();

        $response = $this->get('/attendance');

        $attendance_status = "休憩中";
        $response ->assertSee($attendance_status);
     }

     /** @test
      *  @group attendance_status
      */
     public function attendance_status_clock_out(){
        $response = $this->get('/login');
        $token = csrf_token();

        $response = $this->post('/login', [
            'email' => 'user4@example.com',
            'password' => 'user412345',
            '_token' => $token
        ]);

        $response->assertRedirect('/attendance');
        $this->assertAuthenticated();

        $response = $this->get('/attendance');

        $attendance_status = "退勤済";
        $response ->assertSee($attendance_status);
    }
}
