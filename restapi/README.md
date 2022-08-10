## Laravel 9 Rest API

#### Step 1: Install Laravel

``composer create-project --prefer-dist laravel/laravel restapi``  

#### Step 2: Database Configuration

Create a database and configure the env file.  

#### Step 3: Passport Installation

 - [Documentation](https://laravel.com/docs/9.x/passport#installation)

``composer require laravel/passport``

``php artisan migrate``

``php artisan passport:install``

#### Step 4: Passport Configuration
Open file `App\Models\User`

```
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
}
```

Next, you should call the `Passport::routes` method within the boot method of your `App\Providers\AuthServiceProvider`.

```
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // if (! $this->app->routesAreCached()) {
        //     Passport::routes();
        // }

        /** @var CachesRoutes $app */
        $app = $this->app;
        if (!$app->routesAreCached()) {
            Passport::routes();
        }
    }
}
```

Finally, in your `config/auth.php` configuration file, you should set the `driver` option of the `api` authentication guard to `passport`. This will instruct your application to use Passport's `TokenGuard` when authenticating incoming API requests:

```
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
 
    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
],
```

#### Step 5: Add Table and Model

create migration & model for category, post

``php artisan make:model Category -m``  
``php artisan make:model Post -m``  

**database/migrations/*_create_categories_table.php**
```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Foreign
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
```

**app/Models/Category.php**
```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'user_id'];

    /**
     * The Category that has Many Post.
     */
    public function PostHasMany(){
        return $this->hasMany(Post::class, 'id');
    }
}
```

**database/migrations/*_create_posts_table**
```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->text('image');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            // Foreign
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
```

**app/Models/Post.php**
```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'image', 'category_id', 'user_id',
    ];

    /**
     * The Post that belong to Category.
     */
    public function CategoriesBelongsTo(){
        return $this->belongsTo(Category::class, 'id');
    }
}
```

After create migration, need to run migration ``php artisan migrate``

#### Step 6: Create Controller Files

created a new controller :

``php artisan make:controller Api/LoginController -r``

``php artisan make:controller Api/CategoryController -r``

``php artisan make:controller Api/PostController -r``

#### Step 7: Create API Routes
add new route on that file.

**routes/api.php**

```
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [LoginController::class, 'register']);

    Route::middleware('auth:api')->group(function () {
        Route::resource('user', UserController::class);
        Route::resource('category', CategoryController::class);
        Route::resource('posts', PostController::class);
    });
});
```

#### Step 8: Create Helper Functions

**app/Helpers/Functions.php**

```
<?php

   /**
    * Success response method
    *
    * @param $result
    * @param $message
    * @return \Illuminate\Http\JsonResponse
    */
   function sendResponse($result, $message)
   {
       $response = [
           'success' => true,
           'data'    => $result,
           'message' => $message,
       ];

       return response()->json($response, 200);
   }

   /**
    * Return error response
    *
    * @param       $error
    * @param array $errorMessages
    * @param int   $code
    * @return \Illuminate\Http\JsonResponse
    */
   function sendError($error, $errorMessages = [], $code = 404)
   {
       $response = [
           'success' => false,
           'message' => $error,
       ];

       !empty($errorMessages) ? $response['data'] = $errorMessages : null;

       return response()->json($response, $code);
   }
```

**composer.json**
```
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        },
        "files": [
            "app/Helpers/Functions.php"
        ]
    },
```

``composer dump-autoload``

**app\Http\Controllers\Api\LoginController.php**

```
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class LoginController extends Controller
{
    /**
     * User login API method
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails())
        return sendError('Validation Error.', $validator->errors(), 422);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user             = Auth::user();
            $success['name']  = $user->name;
            $success['token'] = $user->createToken('accessToken')->accessToken;

            return sendResponse($success, 'You are successfully logged in.');
        } else {
            return sendError('Unauthorised', ['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * User registration API method
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails())
        return sendError('Validation Error.', $validator->errors(), 422);

        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $success['name']  = $user->name;
            $message          = 'Yay! A user has been successfully created.';
            $success['token'] = $user->createToken('accessToken')->accessToken;
        } catch (Exception $e) {
            $success['token'] = [];
            $message          = 'Oops! Unable to create a new user.';
        }

        return sendResponse($success, $message);
    }
}
```

#### Step 9: Create Eloquent API Resources

you can use eloquent api resources with api. it will help you to make same response layout of your model object. we used in PostController file. now we have to create it using following command:

``php artisan make:resource CategoryResource``

``php artisan make:resource PostResource``

Now there created a new file with a new folder on following path:

**app/Http/Resources/CategoryResource.php**

```
<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'user_id'     => $this->user_id,
            'created_at'  => $this->created_at->format('d-m-Y')
        ];
    }
}
```

**app\Http\Controllers\Api\CategoryController.php**

```
<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Category::paginate($request->input('results', 10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
        ]);

        if ($validator->fails())
        return sendError('Validation Error.', $validator->errors(), 422);

        try {
            $categories = Category::create([
                'name'      => $request->name,
                'user_id'   => auth()->user()->id
            ]);

            $success = new CategoryResource($categories);
            $message = 'Yay! A category has been successfully created.';
        } catch (Exception $e) {
            $success = [];
            $message = 'Oops! Unable to create a new category.';
        }

        return sendResponse($success, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categories = Category::find($id);

        if (is_null($categories))
        return sendError('Category not found.');

        return sendResponse(new CategoryResource($categories), 'Category retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
        ]);

        if ($validator->fails())
        return sendError('Validation Error.', $validator->errors(), 422);

        try {
            $category = Category::findOrFail($request->category);

            $category->name       = $request->name;
            $category->user_id    = auth()->user()->id;
            $category->save();

            $success = new CategoryResource($category);
            $message = 'Yay! Category has been successfully updated.';
        } catch (Exception $e) {
            $success = [];
            $message = 'Oops, Failed to update the Category.';
        }

        return sendResponse($success, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Category::findOrFail($id)->delete();

            $response = [];
            $message = 'The Category has been successfully deleted.';
        } catch (Exception $e) {
            $response = [];
            $message = 'Oops! Unable to delete Category.';
        }

        return sendResponse($response, $message);
    }
}
```

**app/Http/Resources/PostResource.php**

```
<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'content'       => $this->content,
            'image'         => $this->image,
            'category'      => [
                'id' => $this->CategoriesBelongsTo->id,
                'name' => $this->CategoriesBelongsTo->name,
            ],
            'user_id'       => $this->user_id,
            'created_at'    => $this->created_at->format('d-m-Y')
        ];
    }
}
```

**app\Http\Controllers\Api\PostController.php**

```
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Exception;

use App\Models\Post;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Post::paginate($request->input('results', 10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|min:10',
            'content'       => 'required|min:25',
            'image'         => 'required',
            'category_id'   => 'required'
        ]);

        if ($validator->fails())
        return sendError('Validation Error.', $validator->errors(), 422);

        try {
            $post = Post::create([
                'title'         => $request->title,
                'content'       => $request->content,
                'image'         => $request->image,
                'user_id'       => auth()->user()->id,
                'category_id'   => $request->category_id
            ]);

            $success = [];
            $message = 'Yay! A post has been successfully created.';
        } catch (Exception $e) {
            $success = [];
            $message = 'Oops! Unable to create a new post.';
        }

        return sendResponse($success, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);

            $response = new PostResource($post);
            $message = 'Post retrieved successfully.';
        } catch (Exception $e) {
            $response = [];
            $message = 'Oops! Post not found.';
        }

        return sendResponse($response, $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|min:10',
            'content'       => 'required|min:25',
            'image'         => 'required',
            'category_id'   => 'required'
        ]);

        if ($validator->fails())
        return sendError('Validation Error.', $validator->errors(), 422);

        try {
            $post = Post::findOrFail($request->post);

            $post->title        = $request->title;
            $post->content      = $request->content;
            $post->image        = $request->image;
            $post->user_id      = auth()->user()->id;
            $post->category_id  = $request->category_id;
            $post->save();

            $success = new PostResource($post);
            $message = 'Yay! Post has been successfully updated.';
        } catch (Exception $e) {
            $success = [];
            $message = 'Oops, Failed to update the Post.';
        }

        return sendResponse($success, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Post::findOrFail($id)->delete();

            $response = [];
            $message = 'The Post has been successfully deleted.';
        } catch (Exception $e) {
            $response = [];
            $message = 'Oops! Unable to delete Post.';
        }

        return sendResponse($response, $message);
    }
}
```

Laravel run command :

``php artisan serve``

## PHP Unit

Create php unit command :

``php artisan make:test CategoryTest``

``php artisan make:test PostTest``

**tests/Feature/CategoryTest.php**

```
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
```

**tests/Feature/PostTest.php**

```
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
```

PHPUnit run command :

``./vendor/bin/phpunit``

or

``./vendor/bin/phpunit tests/Feature/CategoryTest.php``

``./vendor/bin/phpunit tests/Feature/PostTest.php``

## API Reference

Set headers

```
'headers' => [
    'Accept'        => 'application/json',
    'Authorization' => 'Bearer '.$token,
]
```

#### Login

```http
  POST api/v1/login
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `email`   | `string` | **Required** |
| `password`| `string` | **Required** |

#### Get item

```http
  POST /api/v1/register
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`    | `string` | **Required**|
| `email`   | `string` | **Required**|
| `password`| `string` | **Required**|

#### Get All Category

```http
  POST /api/v1/category
```

#### Create new category

```http
  POST /api/v1/category
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`    | `string` | **Required**                      |

#### Show detail category

```http
  GET /api/v1/category/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. id to get item      |

#### Update category

```http
  PUT /api/v1/category/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`    | `string` | **Required**                      |


#### Delete Category

```http
  DELETE /api/v1/category${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. id to delete item   |


#### Get all post

```http
  POST /api/v1/posts
```

#### Create new post

```http
  POST /api/v1/posts
```

| Parameter     | Type     | Description                       |
| :------------ | :------- | :-------------------------------- |
| `title`       | `string` | **Required**                      |
| `content`     | `string` | **Required**                      |
| `image`       | `string` | **Required**                      |
| `category_id` | `string` | **Required**                      |
| `user_id`     | `string` | **Required**                      |

#### Show detail post

```http
  GET /api/v1/posts/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. id to get item      |

#### Update post

```http
  PUT /api/v1/posts/${id}
```

| Parameter     | Type     | Description                       |
| :------------ | :------- | :-------------------------------- |
| `title`       | `string` | **Required**                      |
| `content`     | `string` | **Required**                      |
| `image`       | `string` | **Required**                      |
| `category_id` | `string` | **Required**                      |


#### Delete Category

```http
  DELETE /api/v1/posts${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. id to delete item   |


 - [Reference](https://github.com/bdmotaleb/laravel8-rest-api-with-passport)
