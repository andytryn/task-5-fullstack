<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class CategoryControllerTest extends TestCase
{
    public function test_category_create_new()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
        ->post(route('category.store'), [
            'name' => Str::random(10),
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('category.index'));
    }

    public function test_category_create_new_invalid()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
        ->from(route('category.create'))
        ->post(route('category.store'), [
            'name' => Str::random(2),
        ]);
        $response->assertStatus(500);
    }
}
