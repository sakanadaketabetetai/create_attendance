<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     * 
     * @return void
     */


    /** 
     * @test
     * @group user_register
     */
    public function user_register_name_validate(){
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'user@example.com',
            'password' => 'user12345',
            'password_confirmation' => 'user12345',
        ]);
        $response->assertSessionHasErrors('name');
        $this->assertGuest();
    }

    /** 
     * @test
     * @group user_register
     */
    public function user_register_email_validate(){
        $response = $this->post('/register', [
            'name' => '一般ユーザー',
            'email' => 'useremail',
            'password' => 'user12345',
            'password_confirmation' => 'user12345'
        ]);
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** 
     * @test
     * @group user_register
     */
    public function user_register_password_validate(){
        $response = $this->post('register', [
            'name' => '一般ユーザー',
            'email' => 'user@example.com',
            'password' => 'user',
            'password_confirmation' => 'user'
        ]);
        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    /** 
     * @test
     * @group user_register
     */
    public function user_register(){
        $response = $this->post('/register', [
            'name' => '一般ユーザー',
            'email' => 'user@example.com',
            'password' => 'user12345',
            'password_confirmation' => 'user12345'
        ]);

        $response->assertStatus(302); //成功した場合でもリダイレクトされるため302が期待される

        //データベースにユーザーが存在することを確認
        $this->assertDatabaseHas('users', [
            'name' => '一般ユーザー',
            'email' => 'user@example.com',
        ]);

        //パスワードが正しくハッシュ化されて保存されていること
        $user = User::where('email', 'user@example.com')->first();
        $this->assertTrue(Hash::check('user12345', $user->password));

        //ユーザーが認証されていることを確認
        $this->assertAuthenticated();
    }
}
