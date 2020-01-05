@extends('layout.admin')

@section('title', $article->title)

@section('body')
    <div class="container">
        <div class="row justify-content-center">
            <div class="w-75">
                <article>
                    <div class="mt-5 text-center">
                        <img src="{{ $article->getImageUrl() ?? '/img/article/default.svg' }}" alt="post img" class="img-fluid">
                    </div>

                    <h1 class="text-center mt-4">
                        <a href="{{ route('articles.show', compact('article')) }}">{{ $article->title }}</a>
                    </h1>
                    <p class=""><i>{{ $article->created_at }}</i></p>
                    {!! $article->body !!}
                </article>

                @include('comments.admin', ['comments' => $article->comments])

                @include('comments.create', compact('article'))

                @include('pagination.simple')
            </div>
        </div>
    </div>
@endsection
