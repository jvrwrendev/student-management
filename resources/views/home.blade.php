@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <section class="hero">
        <h1>Welcome to {{ config('app.name') }}</h1>
        <p class="hero-subtitle">A demonstration of the Laravel MVC request flow:
            route &rarr; controller &rarr; model &rarr; Blade view.</p>
    </section>

    <section class="section">
        <h2>Latest Posts</h2>
        <p class="section-note">These records are pulled from the database through the
            <code>Post</code> Eloquent model.</p>

        @forelse ($posts as $post)
            <article class="card">
                <h3 class="card-title">
                    <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                </h3>
                <p class="card-meta">
                    {{ $post->created_at->format('F j, Y') }} &middot;
                    <a href="{{ route('posts.show', $post) }}" class="read-more">Read more &rarr;</a>
                </p>
                <p class="card-content">{{ $post->getExcerpt() }}</p>
            </article>
        @empty
            <p>No posts have been published yet.</p>
        @endforelse
    </section>
@endsection