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

class AttendanceClockInTest extends TestCase
{
    use RefreshDatabase;

    private $user1;
    private $user2;
    private $user3;
    /**
     * A basic feature test example.
     *
     * @return void
     */

     //テストユーザーを作成
    public function setUp(): void
    {
        parent::setUp();

        //勤務外のユーザー
        $this->user1 = User::create([
            'name' => 'テストユーザー1',
            'email' => 'user1@example.com',
            'password' => Hash::make('user112345'),
            'email_verified_at' => Carbon::now()
        ]);
        Attendance::create([
            'user_id' => $this->user1->id,
            'clock_in_time' => '08:00:00',
            'clock_out_time' => '17:00:00',
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' => Carbon::yesterday()->toDateString(),
            'attendance_status' => '退勤済',
        ]);

        //退勤済のユーザー
        $this->user2 = User::create([
            'name' => 'テストユーザー2',
            'email' => 'user2@example.com',
            'password' => Hash::make('user212345'),
            'email_verified_at' => Carbon::now()
        ]);
        Attendance::create([
            'user_id' => $this->user2->id,
            'clock_in_time' => '08:00:00',
            'clock_out_time' => '17:00:00',
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' => new Carbon('08:00:00'),
            'attendance_status' => '退勤済',
        ]);

         //勤務外のユーザー
         $this->user3 = User::create([
            'name' => 'テストユーザー3',
            'email' => 'user3@example.com',
            'password' => Hash::make('user312345'),
            'email_verified_at' => Carbon::now()
        ]);
        Attendance::create([
            'user_id' => $this->user3->id,
            'clock_in_time' => '08:00:00',
            'clock_out_time' => '17:00:00',
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' =>Carbon::yesterday()->toDateString(),
            'attendance_status' => '退勤済',
        ]);


        //管理者ユーザーを作成
        Admin::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin12345'),
            'email_verified_at' => Carbon::now()
        ]);

        $this->withoutMiddleware();
    }

    /** @test
     *  @group attendance_clock_in
     */
    public function attendance_clock_in(){
        $response = $this->get('/login');
        $token = csrf_token();

        $response = $this->post('/login', [
            'email' => $this->user1->email,
            'password' => 'user112345',
            '_token' => $token
        ]);
        $response->assertRedirect('/attendance');
        $this->assertAuthenticated();

        $response = $this->get('/attendance');
        $response->assertSee('<button type="submit" class="attendance-content_button-submit">出勤</button>', false);
    }

    /** @test
    *  @group attendance_clock_in
    */
    public function attendance_clock_out(){
        $response = $this->get('/login');
        $token = csrf_token();

        $response = $this->post('/login', [
            'email' => $this->user2->email,
            'password' => 'user212345',
            '_token' => $token
        ]);
        $response->assertRedirect('/attendance');
        $this->assertAuthenticated();

        $response = $this->get('/attendance');
        $response->assertDontSee('<button type="submit" class="attendance-content_button-submit">出勤</button>', false);
    }

    /** @test
     *  @group attendance_clock_in
     */
    public function attendance_clock_in_and_admin_check(){
        //勤務外のユーザでログインして勤務開始
        $response = $this->get('/login');
        $token = csrf_token();
        $response = $this->post('/login', [
            'email' => $this->user3->email,
            'password' => 'user312345',
            '_token' => $token
        ]);
        $response->assertRedirect('/attendance');
        $this->assertAuthenticated();
        $response = $this->get('/attendance/clock_in');


        //管理者ユーザーでログインしてテストユーザーの出勤の日付を確認する
        $response = $this->get('/admin/login');
        $token = csrf_token();

        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'admin12345',
            '_token' => $token
        ]);

        $redirectUrl = '/admin/attendance/list/0';
        $response->assertRedirect($redirectUrl);
        $this->assertAuthenticated('admin');

        $datetime = Carbon::now();
        $date = $datetime->toDateString();
        $formattedDate = $datetime->format('Y年m月d日') . "の勤怠";
        $user3_attendance = Attendance::where('user_id', $this->user3->id)
                                      ->where('date' , $date)
                                      ->first();
                                      
        $this->assertNotNull($user3_attendance);


        $response = $this->get($redirectUrl);
        $response->assertSee($formattedDate);
        $response->assertSee($user3_attendance->clock_in_time);
    }
}
