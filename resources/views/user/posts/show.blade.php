@extends('layouts.app')

@section('header')
    <h2>{{ $post->title }}</h2>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <article>
                    <h1>{{ $post->title }}</h1>
                    <p class="text-muted">
                        Created on {{ $post->created_at->format('F j, Y \a\t H:i') }}
                        @if($post->updated_at->ne($post->created_at))
                            | Updated on {{ $post->updated_at->format('F j, Y \a\t H:i') }}
                        @endif
                    </p>

                    <hr>

                    <div class="post-content">
                        {!! nl2br(e($post->body)) !!}
                    </div>

                    <hr>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Back to Posts</a>
                    </div>
                </article>
            </div>
        </div>
    </div>
@endsection
