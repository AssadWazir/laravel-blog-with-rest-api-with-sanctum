@extends('layouts.app')

@section('header')
    <h2>Dashboard</h2>
@endsection

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1>Welcome, {{ $user->name }}!</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">User Information</h5>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Member Since:</strong> {{ $user->created_at->format('F j, Y') }}</p>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">Edit Profile</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Your Posts</h5>
                        <p class="display-4">{{ $postCount }}</p>
                        <a href="{{ route('posts.index') }}" class="btn btn-primary btn-sm">View All Posts</a>
                        <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm">Create New Post</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
