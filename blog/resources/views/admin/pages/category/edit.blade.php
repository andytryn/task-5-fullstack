@extends('layouts.app')

@section('title', 'Edit ' . Str::limit($category->name, 10). ' - Category')

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
            </div>

            <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-row">
                        <!-- Name -->
                        <div class="form-group col-12">
                            <label for="name">Name</label>
                            <input type="text" name="name" placeholder="Category Name ..." class="form-control @error('name') is-invalid @enderror" value="{{ $category->name }}" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
