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
