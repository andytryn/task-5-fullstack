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
            Category::findOrFail('id', $id)->delete();

            $response = [];
            $message = 'The Category has been successfully deleted.';
        } catch (Exception $e) {
            $response = [];
            $message = 'Oops! Unable to delete Category.';
        }

        return sendResponse($response, $message);
    }
}
