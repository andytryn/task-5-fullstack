<?php

namespace App\Http\Controllers;

use DataTables;
use Exception;

use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
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
            $data = Category::select('*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="category/'. $row->id.'" class="view btn btn-primary btn-sm"><i class="fas fa-folder"></i></a> <a href="category/'. $row->id.'/edit" class="edit btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i></a> <a href="javascript:void(0)" data-id="'.$row->id.'" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.pages.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.category.create');
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
            'name' => 'required|min:3',
        ]);

        try {
            Category::create([
                'name'      => $request->name,
                'user_id'   => auth()->user()->id
            ]);

            $response = 'success';
            $message = 'Yay! A category has been successfully created.';
        } catch (Exception $e) {
            $response = 'error';
            $message = 'Oops! Unable to create a new category.';
        }

        return redirect()
        ->route('category.index')
        ->with($response, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('admin.pages.category.show',compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.pages.category.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:3',
        ]);

        try {
            $category = Category::find($category->id);
            $category->name = $request->name;
            $category->save();


            $response = 'success';
            $message = 'Yay! A category has been successfully created.';
        } catch (Exception $e) {
            $response = 'error';
            $message = 'Oops! Unable to create a new category.';
        }

        return redirect()
        ->route('category.index')
        ->with($response, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            Category::find($category->id)->delete();

            $response = 'success';
            $message = 'The Category has been successfully deleted.';
        } catch (Exception $e) {
            $response = 'error';
            $message = 'Oops! Unable to delete Category.';
        }

        return response()->json([
            $response => $message
        ]);
    }
}
