@extends('layouts.app')

@section('header')
    <h2>Create New Post</h2>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1>Create New Post</h1>

                <form action="{{ route('posts.store') }}" method="POST" class="mt-4">
                    @csrf

                    <!-- Title Field -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Body Field -->
                    <div class="mb-3">
                        <label for="body" class="form-label">Content</label>
                        <textarea class="form-control @error('body') is-invalid @enderror" 
                                  id="body" 
                                  name="body" 
                                  rows="10" 
                                  required>{{ old('body') }}</textarea>
                        @error('body')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <button type="submit" class="btn btn-success">Create Post</button>
                        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
