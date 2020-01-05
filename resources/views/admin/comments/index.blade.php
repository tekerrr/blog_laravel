@extends('layout.admin')

@section('title', 'Комментарии')

@section('content')

    <div class="row pt-3 ml-0 align-items-start">
        @include('pagination.item-per-page-selector')
    </div>

    <div class="row py-3 border-bottom ml-0">
        <div class="col-1"><b>id</b></div>
        <div class="col-1"><b>Cтатья</b></div>
        <div class="col-8"><b>Содежание</b></div>
        <div class="col-2"></div>
    </div>

    @forelse ($comments as $comment)
        @include('admin.comments.item')
    @empty
        <div class="container pt-4">Нет статей</div>
    @endforelse

   @includeWhen($paginator->isNeed(), 'pagination.links', ['items' => $comments])

@endsection
