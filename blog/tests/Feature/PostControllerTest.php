<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use Tests\TestCase;

use App\Models\User;
use App\Models\Category;
use App\Models\Post;

class PostControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_post_create_new()
    {
        Storage::fake('public/images');

        $user = User::factory()->create();
        $category = Category::factory()->create();

        $file = UploadedFile::fake()->image('avatar.jpg')->size(100);


        $response = $this->actingAs($user)
        ->post(route('post.store'), [
            'title' => Str::random(10),
            'content' => Str::random(200),
            'image' => $file,
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        // $this->assertEquals('file/' . $file->hashName(), $file->hashName());
        // Storage::disk('public/images')->assertExists($file->hashName());

        // Assert the file was stored...
        // Storage::disk('public/images')->assertExists($file->hashName());

        // Assert a file does not exist...
        Storage::disk('public/images')->assertMissing('missing.jpg');

        // $response->assertStatus(302);
        // $response->assertRedirect(route('post.index'));
    }
}
