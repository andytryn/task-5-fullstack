<?php

namespace App\Http\Controllers;

use DataTables;
use Exception;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Post::select('*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="post/'. $row->id.'" class="view btn btn-primary btn-sm"><i class="fas fa-folder"></i></a> <a href="post/'. $row->id.'/edit" class="edit btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i></a> <a href="javascript:void(0)" data-id="'.$row->id.'" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.pages.post.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.pages.post.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title'     => 'required|min:3',
            'content'   => 'required|min:10',
            'image'     => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'category'  => 'required',
        ]);

        try {
            $filenameWithExt = $request->file('image')->getClientOriginalName ();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . md5(date('YmdHi')) . '.' . $extension;

            // Public Folder
            // $request->image->move(public_path('images'), $fileNameToStore);

            //Store in Storage Folder
            $request->image->storeAs('public/images', $fileNameToStore);

            $save = new Post;
            $save->title        = $request->title;
            $save->content      = $request->content;
            $save->image        = $fileNameToStore;
            $save->category_id  = $request->category;
            $save->user_id      = auth()->user()->id;
            $save->save();

            $response = 'success';
            $message = 'Yay! A Post has been successfully created.';
        } catch (Exception $e) {
            $response = 'error';
            $message = 'Oops! Unable to create a new Post.';
        }

        return redirect()
        ->route('post.index')
        ->with($response, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $categories = Category::all();
        return view('admin.pages.post.show', compact('post', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();

        return view('admin.pages.post.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $validatedData = $request->validate([
            'title'     => 'required|min:3',
            'content'   => 'required|min:10',
            'image'     => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'category'  => 'required',
        ]);

        try {
            if ($request->hasFile('image')) {
                unlink(public_path('storage/images/' . $post->image));

                $filenameWithExt = $request->file('image')->getClientOriginalName ();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . md5(date('YmdHi')) . '.' . $extension;

                // Public Folder
                // $request->image->move(public_path('images'), $fileNameToStore);

                //Store in Storage Folder
                $request->image->storeAs('public/images', $fileNameToStore);

            } else {
                $fileNameToStore = $post->image;
            }

            $save = Post::find($post->id);
            $save->title        = $request->title;
            $save->content      = $request->content;
            $save->image        = $fileNameToStore;
            $save->category_id  = $request->category;
            $save->user_id      = auth()->user()->id;
            $save->save();

            $response = 'success';
            $message = 'Yay! A category has been successfully created.';
        } catch (Exception $e) {
            $response = 'error';
            $message = 'Oops! Unable to create a new category.' . $e;
        }

        return redirect()
        ->route('post.index')
        ->with($response, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        try {
            Post::find($post->id)->delete();
            unlink(public_path('storage/images/' . $post->image));

            $response = 'success';
            $message = 'The Post has been successfully deleted.';
        } catch (Exception $e) {
            $response = 'error';
            $message = 'Oops! Unable to delete Post.';
        }

        return response()->json([
            $response => $message
        ]);
    }
}
