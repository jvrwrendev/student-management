@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <section class="hero">
        <h1>{{ $post->title }}</h1>
        <p class="card-meta">{{ $post->created_at->format('F j, Y') }}</p>
    </section>

    <section class="section">
        <div class="card post-full">
            {!! nl2br(e($post->content)) !!}
        </div>
        <p class="back-link">
            <a href="{{ route('home') }}">&larr; Back to all posts</a>
        </p>
    </section>
@endsection