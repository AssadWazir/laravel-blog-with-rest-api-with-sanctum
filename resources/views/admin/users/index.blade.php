@extends('layouts.admin')

@section('header')
    <h2>Users Management</h2>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1>All Users</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @if($users->count() > 0)
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role === 'admin')
                                            <span class="badge bg-danger">Admin</span>
                                        @else
                                            <span class="badge bg-info">User</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info">
                        No users found.
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
