@extends('layouts.app')

@section('title', 'Dashboard')

@section('page_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Dashboard</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $posts }}</h3>
                <p>Posts</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="{{ route('post.index') }}" class="small-box-footer">View all <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $categories }}</h3>
                <p>Category</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{ route('category.index') }}" class="small-box-footer">View all <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $total_user }}</h3>
                <p>User Registrations</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <span class="small-box-footer">&nbsp;</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Recently Category (5)</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th width="100x">#</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rec_categories as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center">No Category</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Recently Posts</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Author</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rec_posts as $item)
                        <tr>
                            <td>{{ $item->title }}</td>
                            <td>{!!html_entity_decode(Str::limit($item->content, 50))!!}</td>
                            <td>{{ $item->UserBelongsTo->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No Posts</td>
                        </tr>
                        @endforelse


                        {{-- <tr>
                            <td>Title</td>
                            <td>Content</td>
                            <td>User</td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
