<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    public function test_homepage_successful()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_homepage_unsuccessful()
    {
        $response = $this->get('/non-existing-url');
        $response->assertStatus(200);
    }

    public function test_read_successful()
    {
        $response = $this->get('/read/1');

        $response->assertStatus(200);
    }

    public function test_read_unsuccessful()
    {
        $response = $this->get('/read/non-existing-url');
        $response->assertStatus(200);
    }

    public function test_cat_successful()
    {
        $response = $this->get('/cat/1');

        $response->assertStatus(200);
    }

    public function test_cat_unsuccessful()
    {
        $response = $this->get('/cat/non-existing-url');
        $response->assertStatus(200);
    }
}
