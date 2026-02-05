@extends('layouts.app')

@section('header')
    <h2>My Posts</h2>
@endsection

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1>My Posts</h1>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('posts.create') }}" class="btn btn-success">Create New Post</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($posts->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Title</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>
                                    <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                                        {{ $post->title }}
                                    </a>
                                </td>
                                <td>{{ $post->created_at->format('M j, Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('posts.show', $post) }}" class="btn btn-info btn-sm">View</a>
                                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                <p>You haven't created any posts yet. <a href="{{ route('posts.create') }}">Create your first post</a>!</p>
            </div>
        @endif
    </div>
@endsection
