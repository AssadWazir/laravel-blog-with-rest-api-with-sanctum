@extends('layouts.admin')

@section('header')
    <h2>Admin Dashboard</h2>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1>Welcome, {{ auth()->user()->name }}!</h1>
                <p class="text-muted">You are logged in as <strong>Admin</strong>.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text display-4">{{ $totalUsers }}</p>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">View Users</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Posts</h5>
                        <p class="card-text display-4">{{ $totalPosts }}</p>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-primary btn-sm">View Posts</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
