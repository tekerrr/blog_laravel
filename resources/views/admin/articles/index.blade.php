@extends('layout.master')

@section('title', 'Статьи')

@section('content')
    <div class="container">

        <div class="row justify-content-center my-3">
            <h1>Статьи</h1>
        </div>

        <div class="row pt-3 ml-0 align-items-start">
            <a class="btn btn-outline-primary rounded-0" href="{{ route('admin.articles.create') }}">Создать</a>
            @include('pagination.item-per-page-selector')
        </div>

        <div class="row py-3 border-bottom ml-0">
            <div class="col-1"><b>id</b></div>
            <div class="col-8"><b>Название</b></div>
            <div class="col-1"><b></b></div>
            <div class="col-2"></div>
        </div>

        @forelse ($articles as $article)
            @include('admin.articles.item')
        @empty
            <div class="container pt-4">Нет статей</div>
        @endforelse

        @if ($paginator->isNeed())
            {{ $articles->onEachSide(2)->links('pagination.view') }}
        @endif

    </div>
@endsection
