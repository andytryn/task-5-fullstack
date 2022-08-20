@extends('layouts.app')

@section('title', 'Add New - Post')

@section('page_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Post</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('post.index') }}">Post</a></li>
            <li class="breadcrumb-item active">Add New</li>
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
            </div>

            <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-row">

                        <!-- Name -->
                        <div class="form-group col-12">
                            <label for="title">Title</label>
                            <input type="text" name="title" placeholder="Title ..." class="form-control @error('title') is-invalid @enderror" />
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <div class="form-group col-12">
                            <label for="category">Category</label>
                            <select class="form-control select2" name="category" style="width: 100%;">
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Image --}}
                        <div class="form-group col-12">
                            <label for="image">Image Cover</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image"/>
                                    <label class="custom-file-label" for="image">Choose file</label>
                                </div>
                                @error('image')
                                    <div style="width: 100%;margin-top: .25rem;font-size: 80%;color: #dc3545;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="form-group col-12">
                            <label for="content">Content</label>
                            <textarea name="content" id="content_textarea" class="form-control @error('content') is-invalid @enderror"></textarea>
                            @error('content')
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

@push('stylesheets')
<!-- Select2 -->
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/select2/css/select2.min.css" />
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" />

<!-- summernote -->
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/summernote/summernote-bs4.min.css" />
@endpush

@push('scripts')
<!-- Select2 -->
<script src="https://adminlte.io/themes/v3/plugins/select2/js/select2.full.min.js"></script>
<!-- bs-custom-file-input -->
<script src="https://adminlte.io/themes/v3/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- Summernote -->
<script src="https://adminlte.io/themes/v3/plugins/summernote/summernote-bs4.min.js"></script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2({
            theme: "bootstrap4",
        });

        //Initialize Select2 Elements
        $(".select2bs4").select2({
            theme: "bootstrap4",
        });

        bsCustomFileInput.init();

        // Summernote
        $('#content_textarea').summernote({
            placeholder: 'Text here ...',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });


    });
</script>
@endpush
