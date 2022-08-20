<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthenticationTest extends TestCase
{
    public function test_login()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_login_users_successful()
    {
        $email = Str::random(10).'@gmail.com';
        $password = Hash::make('password');

        $response = $this->post('/register', [
            'name' => Str::random(10),
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_login_users_unsuccessful()
    {
        $response = $this->post('/login', [
            'email' => Str::random(10).'@gmail.com',
            'password' => 'password',
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
