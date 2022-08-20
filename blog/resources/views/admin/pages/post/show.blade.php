@extends('layouts.app')

@section('title', Str::limit($post->title, 10). " - Category")

@section('page_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Category</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('post.index') }}">Category</a></li>
            <li class="breadcrumb-item active">{{ $post->id }} &bull; {{ Str::limit($post->title, 10) }}</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('post.index') }}" class="btn btn-secondary"><i class="fas fa-angle-left pr-1"></i> Back</a>
                <a href="{{ route('post.edit', $post->id) }}" class="btn btn-secondary float-right"><i class="fas fa-pencil-alt"></i> Edit</a>
            </div>

            <form>
                <div class="card-body">
                    <div class="form-row">
                        <!-- Name -->
                        <div class="form-group col-12">
                            <label for="title">Title</label>
                            <input type="text" name="title" placeholder="Title ..." class="form-control" value="{{ $post->title }}" disabled/>
                        </div>

                        <!-- Category -->
                        <div class="form-group col-12">
                            <label for="category">Category</label>
                            <input type="text" name="category" placeholder="Category ..." class="form-control" value="{{ $post->CategoriesBelongsTo->name }}" disabled/>
                        </div>

                        <!-- Image -->
                        <div class="form-group col-12">
                            <label for="category">Image</label><br/>
                            <img class="img-fluid pad" src="{{ url('storage/images/' . $post->image) }}" alt="Photo">
                        </div>

                        <!-- Content -->
                        <div class="form-group col-12">
                            <label for="content">Content</label>
                            <textarea name="content" id="content_textarea" class="form-control @error('content') is-invalid @enderror">{{ $post->content }}</textarea>
                        </div>

                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('stylesheets')
<!-- summernote -->
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/summernote/summernote-bs4.min.css" />
@endpush

@push('scripts')
<!-- Summernote -->
<script src="https://adminlte.io/themes/v3/plugins/summernote/summernote-bs4.min.js"></script>

<script>
    $(function () {
        // Summernote
        $('#content_textarea').summernote('disable');

    });
</script>
@endpush
