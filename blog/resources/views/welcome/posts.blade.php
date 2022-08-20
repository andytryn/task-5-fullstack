@extends('layouts.front')

@section('title', $posts->title)

@section('content-header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ $posts->title }}</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="col-md-9">
    <div class="row">
        <div class="col-12">
            <div class="invoice p-3 mb-3">
                <div class="row">
                    <img class="card-img-top shadow" alt="Cover" src="{{ url('storage/images/' . $posts->image) }}" />
                </div>
                <br/>
                <div class="row">
                    <div class="col-sm-4">
                        Author
                        <address>
                            <strong>{{ $posts->UserBelongsTo->name }}</strong><br />
                        </address>
                    </div>
                    <div class="col-sm-4">
                        Category
                        <address>
                            <strong><a href="{{ route('welcome.category', $posts->CategoriesBelongsTo->id) }}">{{ $posts->CategoriesBelongsTo->name }}</a></strong><br />
                        </address>
                    </div>
                    <div class="col-sm-4">
                        Publish
                        <address>
                            <strong>{{ $posts->created_at }}</strong><br />
                        </address>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12 order-1 order-md-2">
                        <p class="text-muted">
                            {!!html_entity_decode($posts->content)!!}
                        </p>
                    </div>
                </div>
            </div>
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
