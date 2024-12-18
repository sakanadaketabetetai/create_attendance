<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Attendance;
use Carbon\Carbon;
use Tests\TestCase;

class AttendanceClockOutTest extends TestCase
{
    use RefreshDatabase;

    private $user1;
    private $user2;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user1 = User::create([
            'name' => 'テスト1ユーザー',
            'email' => 'user1@example.com',
            'password' => Hash::make('user112345')
        ]);
        Attendance::create([
            'user_id' => $this->user1->id,
            'clock_in_time' => '08:00:00',
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' => Carbon::today()->toDateString(),
            'attendance_status' => '出勤中',
        ]);

        $this->user2 = User::create([
            'name' => 'テスト2ユーザー',
            'email' => 'user2@example.com',
            'password' => Hash::make('user212345')
        ]);
        Attendance::create([
            'user_id' => $this->user2->id,
            'clock_in_time' => '08:00:00',
            'clock_out_time' => '17:00:00',
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' => Carbon::yesterday()->toDateString(),
            'attendance_status' => '退勤済',
        ]);

        Admin::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin12345'),
            'email_verified_at' => Carbon::now()
        ]);

        $this->withoutMiddleware();
    }

    /** @test
     *  @group attendance_clock_out
     */
    public function attendance_clock_out()
    {
        $response = $this->post('/login', [
            'email' => $this->user1->email,
            'password' => 'user112345'
        ]);
        $response->assertRedirect('/attendance');
        $this->assertAuthenticated();

        $response = $this->get('/attendance');
        $response->assertSee('<button type="submit" class="attendance-content_button-submit">退勤</button>' , false);

        $response = $this->get('/attendance/clock_out');
        $date = Carbon::today()->toDateString();
        $time = Carbon::now()->toTimeString();

        $this->assertDatabaseHas('attendances', [
            'user_id' => $this->user1->id,
            'clock_in_time' => '08:00:00',
            'clock_out_time' => $time,
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' => $date,
            'attendance_status' => '退勤済'
        ]);

        $response = $this->get('/attendance');
        $response->assertSee('退勤済');
    }

    /** @test
     *  @group attendance_clock_out
     */
    public function attendance_clock_in_and_clock_out()
    {
        $response = $this->post('/login', [
            'email' => $this->user2->email,
            'password' => 'user212345'
        ]);

        $response->assertRedirect('/attendance');
        $this->assertAuthenticated();

        $response = $this->get('/attendance');
        $response = $this->get('/attendance/clock_in');
        $clock_in_time = Carbon::now()->toTimeString();
        $clock_in_date = Carbon::today()->toDateString();
        $this->assertDatabaseHas('attendances', [
            'user_id' => $this->user2->id,
            'clock_in_time' => $clock_in_time,
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' => $clock_in_date,
            'attendance_status' => '出勤中'
        ]);

        $response = $this->get('/attendance');
        $response->assertSee('出勤中');

        $response = $this->get('/attendance/clock_out');
        $clock_out_time = Carbon::now()->toTimeString();
        $this->assertDatabaseHas('attendances', [
            'user_id' => $this->user2->id,
            'clock_out_time' => $clock_out_time,
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' => $clock_in_date,
            'attendance_status' => '退勤済'
        ]);
        $response = $this->get('/attendance');
        $response->assertSee('退勤済');

        $response = $this->get('/admin/login');
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'admin12345',
        ]);

        $response->assertRedirect('/admin/attendance/list/0');
        $this->assertAuthenticated('admin');

        $response = $this->get('/admin/attendance/list/0');

        $date = Carbon::today()->format('Y/m/d');
        $response->assertSee($date);
        $response->assertSee($clock_out_time);
    }
}
