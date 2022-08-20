<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Count
        $categories = Category::count();
        $posts = Post::count();
        $total_user = User::count();

        // Recently
        $rec_categories = Category::orderBy('id', 'desc')->limit(5)->get();
        $rec_posts = Post::orderBy('id', 'desc')->limit(5)->get();

        // $getPosts = Post::find(1);

        // dd(
        //     $getPosts->CategoriesBelongsTo->name
        // );

        return view('admin.pages.dashboard.index', compact('categories', 'posts', 'total_user', 'rec_categories', 'rec_posts'));
    }
}
