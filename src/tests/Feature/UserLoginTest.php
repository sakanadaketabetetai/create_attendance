<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tests\TestCase;

class UserLoginTest extends TestCase
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

        //テストユーザーを作成
        User::create([
            'name' => '一般ユーザー',
            'email' => 'user@example.com',
            'password' => Hash::make('user12345')
        ]);
    }
    /** @test 
     *  @group user_login
     */ 
    public function user_login_email_validate(){
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'user12345'
        ]);
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test 
     *  @group user_login
     */ 
    public function user_login_password_validate(){
        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => ''
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    /** @test 
     *  @group user_login
     */ 
    public function user_login_email_false_validate(){
        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'user12345'
        ]);
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
}
