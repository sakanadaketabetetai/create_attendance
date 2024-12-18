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

class AttendanceRestTest extends TestCase
{
    use RefreshDatabase;

    private $user1;
    private $user2;
    private $user3;
    private $user4;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp():void
    {
        parent::setUp();

        //勤務中のユーザーを作成
        $this->user1 = User::create([
            'name' => 'テスト1ユーザー',
            'email' => 'user1@example.com',
            'password' => Hash::make('user112345'),
            'email_verified_at' => Carbon::now()
        ]);
        Attendance::create([
            'user_id' => $this->user1->id,
            'clock_in_time' => '08:00:00',
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' => Carbon::today()->toDateString(),
            'attendance_status' => '出勤中',
        ]);

        //勤務中のユーザーを作成
        $this->user2 = User::create([
            'name' => 'テスト2ユーザー',
            'email' => 'user2@example.com',
            'password' => Hash::make('user212345'),
            'email_verified_at' => Carbon::now()
        ]);
        Attendance::create([
            'user_id' => $this->user2->id,
            'clock_in_time' => '08:00:00',
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' => Carbon::today()->toDateString(),
            'attendance_status' => '出勤中',
        ]);
        //勤務中のユーザーを作成
        $this->user3 = User::create([
            'name' => 'テスト3ユーザー',
            'email' => 'user3@example.com',
            'password' => Hash::make('user312345'),
            'email_verified_at' => Carbon::now()
        ]);
        Attendance::create([
            'user_id' => $this->user3->id,
            'clock_in_time' => '08:00:00',
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' => Carbon::today()->toDateString(),
            'attendance_status' => '出勤中',
        ]);

        //勤務中のユーザーを作成
        $this->user4 = User::create([
            'name' => 'テスト4ユーザー',
            'email' => 'user4@example.com',
            'password' => Hash::make('user412345'),
            'email_verified_at' => Carbon::now()
        ]);
        Attendance::create([
            'user_id' => $this->user4->id,
            'clock_in_time' => '08:00:00',
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'date' => Carbon::today()->toDateString(),
            'attendance_status' => '出勤中',
        ]);

        //管理者作成
        Admin::create([
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin12345'),
            'email_verified_at' => Carbon::now()
        ]);

        $this->withoutMiddleware();
    }

    /** @test
     *  @group attendance_rest
     */
    public function Attendance_rest_start(){
        $response = $this->post('/login',[
            'email' => $this->user1->email,
            'password' => 'user112345',
        ]);

        $response->assertRedirect('/attendance');
        $this->assertAuthenticated();

        $response = $this->get('/attendance');
        $response->assertSee('<button type="submit" class="attendance-content_button-submit">休憩入</button>', false);

        $date = Carbon::today()->toDateString();
        $response = $this->get('/attendance/rest_start');
        $response = $this->get('/attendance');
        $datetime = new Carbon();
        $time = $datetime->toTimeString();

        $user1_attendance  = Attendance::where('user_id', $this->user1->id)
                                        ->where('date' , $date)
                                        ->first();
        $this->assertNotNull($user1_attendance);

        $this->assertDatabaseHas('rests', [
            'user_id' => $this->user1->id,
            'attendance_id' => $user1_attendance->id,
            'rest_start_time' => $time
        ]);
        $response->assertSee("休憩中");
    }

    /** @test
     *  @group attendance_rest
     */
    public function attendance_rest_start_and_rest_end(){
        //テストユーザー2でログイン処理
        $response = $this->post('/login', [
            'email' => $this->user2->email,
            'password' => 'user212345',
        ]);
        $response->assertRedirect('/attendance');
        $this->assertAuthenticated();

        $response = $this->get('/attendance');
        $response->assertSee('<button type="submit" class="attendance-content_button-submit">休憩入</button>', false);
        $date = Carbon::today()->toDateString();

        //休憩開始
        $response = $this->get('/attendance/rest_start');
        $response = $this->get('/attendance');
        
        $rest_start_time = Carbon::now()->toTimeString();

        $user2_attendance = Attendance::where('user_id', $this->user2->id)
                                     ->where('date', $date)
                                     ->first();
        $this->assertDatabaseHas('rests', [
            'user_id' => $this->user2->id,
            'attendance_id' => $user2_attendance->id,
            'rest_start_time' => $rest_start_time
        ]);
        $response->assertSee('休憩中');
        $response->assertSee('<button type="submit" class="attendance-content_button-submit">休憩戻</button>', false);

        //休憩終了
        $response = $this->get('/attendance/rest_end');
        $response = $this->get('/attendance');

        $rest_end_time = Carbon::now()->toTimeString();
        $this->assertDatabaseHas('rests', [
            'user_id' => $this->user2->id,
            'attendance_id' => $user2_attendance->id,
            'rest_end_time' => $rest_end_time,
        ]);
        $response->assertSee('出勤中');
        $response->assertSee('<button type="submit" class="attendance-content_button-submit">休憩入</button>', false);
    }

    /** @test
     *  @group attendance_rest
     */
    public function attendance_rest_start_and_rest_end_and_rest_start(){
        //テストユーザー3でログイン処理
        $response = $this->post('/login', [
            'email' => $this->user3->email,
            'password' => 'user312345',
        ]);
        $response->assertRedirect('/attendance');
        $this->assertAuthenticated();

        $response = $this->get('/attendance');
        $response->assertSee('<button type="submit" class="attendance-content_button-submit">休憩入</button>', false);
        $date = Carbon::today()->toDateString();

        //休憩開始
        $response = $this->get('/attendance/rest_start');
        $response = $this->get('/attendance');
        
        $rest_start_time = Carbon::now()->toTimeString();

        $user3_attendance = Attendance::where('user_id', $this->user3->id)
                                     ->where('date', $date)
                                     ->first();
        $this->assertDatabaseHas('rests', [
            'user_id' => $this->user3->id,
            'attendance_id' => $user3_attendance->id,
            'rest_start_time' => $rest_start_time
        ]);
        $response->assertSee('休憩中');
        $response->assertSee('<button type="submit" class="attendance-content_button-submit">休憩戻</button>', false);

        //休憩終了
        $response = $this->get('/attendance/rest_end');
        $response = $this->get('/attendance');

        $rest_end_time = Carbon::now()->toTimeString();
        $this->assertDatabaseHas('rests', [
            'user_id' => $this->user3->id,
            'attendance_id' => $user3_attendance->id,
            'rest_end_time' => $rest_end_time,
        ]);
        $response->assertSee('出勤中');
        $response->assertSee('<button type="submit" class="attendance-content_button-submit">休憩入</button>', false);

        //再度休憩開始
        $response = $this->get('/attendance/rest_start');
        $response = $this->get('/attendance');
        
        $rest_start_time = Carbon::now()->toTimeString();

        $this->assertDatabaseHas('rests', [
            'user_id' => $this->user3->id,
            'attendance_id' => $user3_attendance->id,
            'rest_start_time' => $rest_start_time
        ]);
        $response->assertSee('休憩中');
        $response->assertSee('<button type="submit" class="attendance-content_button-submit">休憩戻</button>', false);
    }

    /** @test
     *  @group attendance_rest
     */
    public function attendance_admin_rest_check(){
        //テストユーザー4でログイン処理
        $response = $this->post('/login', [
            'email' => $this->user4->email,
            'password' => 'user412345',
        ]);
        $response->assertRedirect('/attendance');
        $this->assertAuthenticated();

        $response = $this->get('/attendance');
        $response->assertSee('<button type="submit" class="attendance-content_button-submit">休憩入</button>', false);
        $date = Carbon::today()->toDateString();

        //休憩開始
        $response = $this->get('/attendance/rest_start');
        $response = $this->get('/attendance');
        
        $rest_start_time = Carbon::now()->toTimeString();

        $user4_attendance = Attendance::where('user_id', $this->user4->id)
                                     ->where('date', $date)
                                     ->first();
        $this->assertDatabaseHas('rests', [
            'user_id' => $this->user4->id,
            'attendance_id' => $user4_attendance->id,
            'rest_start_time' => $rest_start_time
        ]);
        $response->assertSee('休憩中');
        $response->assertSee('<button type="submit" class="attendance-content_button-submit">休憩戻</button>', false);

        //休憩終了
        $response = $this->get('/attendance/rest_end');
        $response = $this->get('/attendance');

        $rest_end_time = Carbon::now()->toTimeString();
        $this->assertDatabaseHas('rests', [
            'user_id' => $this->user4->id,
            'attendance_id' => $user4_attendance->id,
            'rest_end_time' => $rest_end_time,
        ]);
        $response->assertSee('出勤中');
        $response->assertSee('<button type="submit" class="attendance-content_button-submit">休憩入</button>', false);

        //管理者ログイン
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'admin12345'
        ]);
        $response->assertRedirect('/admin/attendance/list/0');
        $this->assertAuthenticated('admin');

        $response = $this->get('/admin/attendance/list/0');

        $date = Carbon::today()->toDateString();
        $formattedDate = Carbon::today()->format('Y年m月d日') . "の勤怠";
        $attendance = Attendance::where('user_id', $this->user4->id)->where('date', $date)->get();
        $adjustAttendances = Attendance::adjustAttendance($attendance);

        $user4_attendance_adjusted = $adjustAttendances->firstWhere('user_id', $this->user4->id);
        $response->assertSee($formattedDate);
        $response->assertSee($user4_attendance_adjusted->rest_sum);
    }
}
