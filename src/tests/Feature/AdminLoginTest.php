<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Tests\TestCase;

class AdminLoginTest extends TestCase
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

        //管理者ユーザーを作成
        Admin::create([
            'name' => '管理者ユーザー',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin12345')
        ]);
    }

     /** @test
      *  @group admin_login
      */
      public function admin_login_email_validate()
      {
        $response = $this->post('/admin/login', [
            'email' => '',
            'password' => 'admin12345'
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
      }

     /** @test
      *  @group admin_login
      */
      public function admin_login_password_validate()
      {
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => ''
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
      }

     /** @test
      *  @group admin_login
      */
      public function admin_login_email_failed_validate()
      {
        $response = $this->post('/admin/login', [
            'email' => 'user@example.com',
            'password' => 'admin12345'
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
      }
}
