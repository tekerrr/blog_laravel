@extends('layout.master')

@section('title', 'Главная')

@section('content')
    <div class="container pt-4">

        @forelse ($articles as $article)
            @include('articles.item')
        @empty
            <div class="container pt-4">Нет статей</div>
        @endforelse

        {{ $articles->onEachSide(2)->links('pagination.view') }}

        @includeWhen(! auth()->check() || ! auth()->user()->isSubscriber(), 'subscription.view')

    </div>
@endsection
