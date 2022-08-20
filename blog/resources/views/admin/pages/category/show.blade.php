@extends('layouts.app')

@section('title', Str::limit($category->name, 10). " - Category")

@section('page_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Category</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category.index') }}">Category</a></li>
            <li class="breadcrumb-item active">{{ $category->id }} &bull; {{ Str::limit($category->name, 10) }}</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('category.index') }}" class="btn btn-secondary"><i class="fas fa-angle-left pr-1"></i> Back</a>
                <a href="{{ route('category.edit', $category->id) }}" class="btn btn-secondary float-right"><i class="fas fa-pencil-alt"></i> Edit</a>
            </div>

            <form>
                <div class="card-body">
                    <div class="form-row">
                        <!-- Name -->
                        <div class="form-group col-12">
                            <label for="name">Name</label>
                            <input type="text" name="name" placeholder="Category Name ..." class="form-control" value="{{ $category->name }}" disabled/>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
