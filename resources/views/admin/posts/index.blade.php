@extends('layouts.admin')

@section('header')
    <h2>Posts Management</h2>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1>All Posts</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @if($posts->count() > 0)
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                                <tr>
                                    <td>{{ $post->id }}</td>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->user->name }}</td>
                                    <td>{{ $post->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info">
                        No posts found.
                    </div>
                @endif
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>
    </div>
@endsection
