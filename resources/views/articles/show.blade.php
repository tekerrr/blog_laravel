@extends('layout.master')

@section('title', $article->title)

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="w-75">
                <article>
                    <div class="mt-5 text-center">
                        <img src="{{ $article->image->path ?? '/img/article/default.svg' }}" alt="post img" class="img-fluid">
                    </div>
                    <h1 class="text-center mt-4">{{ $article->title }}</h1>
                    <p class=""><i>{{ $article->created_at }}</i></p>
                    {{ $article->body }}
                </article>

                @include('pagination.simple')
            </div>
        </div>
    </div>
@endsection
