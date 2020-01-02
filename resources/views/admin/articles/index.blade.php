@extends('layout.admin')

@section('title', 'Статьи')

@section('content')

    @include('admin.content-header', ['create_path' => route('admin.articles.create')])

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

   @includeWhen($paginator->isNeed(), 'pagination.links', ['items' => $articles])

@endsection
