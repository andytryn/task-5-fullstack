<?php

namespace Tests\Feature;

use Tests\TestCase;

class CategoryTest extends TestCase
{
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
     * test index category.
     *
     * @return void
     */
    public function test_index_category()
    {
        $response = $this->withHeaders([
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '. $this->authenticate()['token'],
        ])->json('GET','api/v1/category');

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
     * test update category.
     *
     * @return void
     */
    public function test_update_category()
    {
        $response = $this->withHeaders([
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '. $this->authenticate()['token'],
        ])->json('PUT','api/v1/category/' . self::$category_id,[
            'name'          => 'Kategori Test Unit',
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
        ])->json('GET','api/v1/category/' . self::$category_id);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /**
     * test delete category.
     *
     * @return void
     */
    public function test_delete_category()
    {
        $response = $this->withHeaders([
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '. $this->authenticate()['token'],
        ])->json('DELETE','api/v1/category/' . self::$category_id);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }
}
