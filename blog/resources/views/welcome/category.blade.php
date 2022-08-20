@extends('layouts.front')

@section('title', 'Category: ' . $categories->name)

@section('content-header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="far fa-circle text-danger"></i> Category: {{ $categories->name }}</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="col-md-9">
    <div class="row">

        @forelse ($posts->where('category_id', '=', $categories->id) as $item)
        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
            <div class="card bg-light d-flex flex-fill shadow-none">
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="card mb-2 bg-gradient-dark">
                            <img class="card-img-top shadow" alt="Cover" src="{{ url('storage/images/' . $item->image) }}" />
                            <div class="card-img-overlay d-flex flex-column justify-content-end">
                                <h5 class="card-title">
                                    <span class="bg-primary" style="padding-left: 3%; padding-right: 3%;">{{ $item->title }}</span>
                                </h5>
                                <p class="card-text text-white pb-2 pt-1"></p>
                                <a href="{{ route('welcome.category', $item->category_id) }}" class="btn btn-info btn-block btn-flat btn-xs" style="width: 49%;"><i class="far fa-circle text-danger"></i> {{ $item->CategoriesBelongsTo->name }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <span class="bg-primary" style="padding-left: 1%; padding-right: 1%;">{{ $item->created_at }}</span>
                        <span class="text-muted" style="margin-left: 10px;"><b>{{ $item->UserBelongsTo->name }}</b></span>
                    </div>
                    <div class="row">
                        <p class="text-muted text-sm">
                            {!!html_entity_decode(Str::limit($item->content, 50))!!}
                        </p>
                    </div>
                    <a href="{{ route('welcome.read', $item->id) }}" class="btn btn-xs btn-primary"> Read More</a>
                </div>
            </div>
        </div>
        @empty
        <div class="error-page">
            <h2 class="headline text-danger">👀</h2>
            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops!</h3>
                <p>Post is empty, pelase write a post</p>
            </div>
        </div>
        @endforelse

    </div>
    <div class="row">
        <div class="col-12">
            {!! $posts->withQueryString()->links('pagination::bootstrap-5') !!}
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Categories</h3>
        </div>

        <div class="card-body p-0">
            <ul class="nav nav-pills flex-column">
                @forelse ($category as $item)
                <li class="nav-item">
                    <a href="{{ route('welcome.category', $item->id) }}" class="nav-link">
                        <i class="far fa-circle text-danger"></i> {{ $item->name }}
                        <span class="badge bg-primary float-right">{{ $item->PostHasMany->count() }}</span>
                    </a>
                </li>
                @empty
                <li class="nav-item">
                    <span class="nav-link text-danger text-center">
                        Category is empty
                    </span>
                </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
