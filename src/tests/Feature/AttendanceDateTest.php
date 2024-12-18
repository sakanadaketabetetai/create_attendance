<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tests\TestCase;
use Carbon\Carbon;

class AttendanceDateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp():void
    {
        parent::setUp();

        User::create([
            'name' => '一般ユーザー',
            'email' => 'user@example.com',
            'password' => Hash::make('user12345'),
            'email_verified_at' => Carbon::now(),
        ]);

        $this->withoutMiddleware(); //ミドルウェアを一時的に無効化
    }

    /** @test
     *  @group attendance_date
     */
    public function attendance_date()
    {
        $response = $this->get('/login');
        $token = csrf_token();

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'user12345',
            '_token' => $token
        ]);

        $response->assertRedirect('/attendance');
        $this->assertAuthenticated();

        //リダイレクト後のページ取得
        $response = $this->get('/attendance');

        //ビュー内の$dateと$timeが正しいことを確認
        $datetime = Carbon::now();
        $formattedDate = $datetime->format('Y年m月d日');
        $weekMap = ['日','月','火', '水','木', '金', '土'];
        $dayOfWeek = $weekMap[$datetime->dayOfWeek];
        $currentDate = $formattedDate . '(' . $dayOfWeek . ')';
        $currentTime = $datetime->format('H:i');

        //HTMLの内容を確認
        $response->assertSee($currentDate);
        $response->assertSee($currentTime);
    }
}
