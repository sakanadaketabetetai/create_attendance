<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Requests\AdminAttendanceRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use Tests\TestCase;

class UserAuthValidatorTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        //テストユーザーと管理者を作成
        User::create([
            'name' => '一般ユーザー',
            'email' => 'user@example.com',
            'password' => Hash::make('password123')
        ]);
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/attendance');
        $this->assertAuthenticatedAs(User::where('email', 'user@example.com')->first());
    }

    /** @test */
    public function admin_can_login_with_valid_credentials()
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'adminpass123'
        ]);

        $response->assertRedirect('/admin/attendance/list/{num}');
        $this->assertAuthenticatedAs(Admin::where('email', 'admin@example.com'), 'admin');
    }

    /** @test */
    public function login_fails_with_inbalid_crendentials()
    {
        $response = $this->post('/login' , [
            'email' => 'user@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');//エラーメッセージが表示される
        $this->assertGuest(); //認証されてないことを確認
    }

    /** @test */
    public function validation_errors_are_displayed_on_login(){
        $response = $this->post('/login', [
            'email' => '',
            'password' => ''
        ]);
        $response->assertSessionHasErrors(['email' ,'password']); //バリデーションエラーメッセージが表示されることを確認
        $this->assertGuest();
    }
}
