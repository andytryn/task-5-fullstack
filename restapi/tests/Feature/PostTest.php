<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;

class PostTest extends TestCase
{
    private static $posts_id = null;
    private static $category_id = null;

    /**
     * Authenticate user.
     *
     * @return void
     */
    protected function authenticate()
    {
        if (!auth()->attempt([
            'email'     => 'admin@gmail.com',
            'password'  => 'rahasia123'
            ])) {
            return response([
                'message' => 'Login credentials are invaild'
            ]);
        }

        $data = [
            'user_id' => auth()->user()->id,
            'token' => auth()->user()->createToken('authToken')->accessToken,
        ];

        return $data;
    }

    /**
     * test index posts.
     *
     * @return void
     */
    public function test_index_posts()
    {
        $response = $this->withHeaders([
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '. $this->authenticate()['token'],
        ])->json('GET','api/v1/posts');

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /**
     * test create category.
     *
     * @return void
     */
    public function test_create_category()
    {
        $response = $this->withHeaders([
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '. $this->authenticate()['token'],
        ])->json('POST','api/v1/category',[
            'name'          => 'Kategori',
            'user_id'       => $this->authenticate()['user_id']
        ]);

        self::$category_id = $response->original['data']['id'];

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /**
     * test create posts.
     *
     * @return void
     */
    public function test_create_posts()
    {
        $response = $this->withHeaders([
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '. $this->authenticate()['token'],
        ])->json('POST','api/v1/posts',[
            'title'         => 'Where does it come from?',
            'content'       => 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.',
            'image'         => 'lorem.jpg',
            'category_id'   => self::$category_id,
            'user_id'       => $this->authenticate()['user_id']
        ]);

        self::$posts_id = Post::all()->last()->id;

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /**
     * test update posts.
     *
     * @return void
     */
    public function test_update_posts()
    {
        $response = $this->withHeaders([
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '. $this->authenticate()['token'],
        ])->json('PUT','api/v1/posts/' . self::$posts_id,[
            'title'         => 'Dari mana asalnya?',
            'content'       => 'Tidak seperti anggapan banyak orang, Lorem Ipsum bukanlah teks-teks yang diacak. Ia berakar dari sebuah naskah sastra latin klasik dari era 45 sebelum masehi, hingga bisa dipastikan usianya telah mencapai lebih dari 2000 tahun. Richard McClintock, seorang professor Bahasa Latin dari Hampden-Sidney College di Virginia, mencoba mencari makna salah satu kata latin yang dianggap paling tidak jelas, yakni consectetur, yang diambil dari salah satu bagian Lorem Ipsum. Setelah ia mencari maknanya di di literatur klasik, ia mendapatkan sebuah sumber yang tidak bisa diragukan. Lorem Ipsum berasal dari bagian 1.10.32 dan 1.10.33 dari naskah "de Finibus Bonorum et Malorum" (Sisi Ekstrim dari Kebaikan dan Kejahatan) karya Cicero, yang ditulis pada tahun 45 sebelum masehi. BUku ini adalah risalah dari teori etika yang sangat terkenal pada masa Renaissance. Baris pertama dari Lorem Ipsum, "Lorem ipsum dolor sit amet..", berasal dari sebuah baris di bagian 1.10.32.',
            'image'         => 'ipsum.jpg',
            'category_id'   => self::$category_id,
        ]);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /**
     * test find product.
     *
     * @return void
     */
    public function test_find_product()
    {
        $response = $this->withHeaders([
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '. $this->authenticate()['token'],
        ])->json('GET','api/v1/posts/' . self::$posts_id);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /**
     * test delete posts.
     *
     * @return void
     */
    public function test_delete_posts()
    {
        $response = $this->withHeaders([
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '. $this->authenticate()['token'],
        ])->json('DELETE','api/v1/posts/' . self::$posts_id);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }
}
