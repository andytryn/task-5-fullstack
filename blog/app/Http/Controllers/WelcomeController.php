<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Count
        // Category::orderBy('id', 'desc')->limit(5)->get();
        $categories = Category::all();
        $posts = Post::orderBy('id', 'desc')->paginate(5);
        $total_user = User::count();

        // Recently
        $rec_categories = Category::orderBy('id', 'desc')->limit(5)->get();
        $rec_posts = Post::orderBy('id', 'desc')->limit(5)->get();

        // dd(
        //     $categories
        // );

        return view('welcome.index', compact('categories', 'posts', 'total_user', 'rec_categories', 'rec_posts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cat($id)
    {
        $categories = Category::FindOrFail($id);
        $category = Category::all();
        $posts = Post::paginate(5);

        return view('welcome.category', compact('categories', 'category', 'posts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function read($id)
    {
        $posts = Post::FindOrFail($id);
        $category = Category::all();

        return view('welcome.posts', compact('posts', 'category'));
    }

}
