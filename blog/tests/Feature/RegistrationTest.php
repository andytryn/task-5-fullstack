<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationTest extends TestCase
{
    public function test_register()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_register_new_users()
    {
        $password = Hash::make('password');

        $response = $this->post('/register', [
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => $password,
            'password_confirmation' => $password,
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
