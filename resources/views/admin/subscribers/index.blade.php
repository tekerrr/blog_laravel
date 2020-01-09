@extends('layout.admin')

@section('title', 'Подписчики')

@section('content')

    <div class="row pt-3 ml-0 align-items-start">
        @include('pagination.item-per-page-selector')
    </div>

    <div class="row py-3 border-bottom ml-0">
        <div class="col-1"><b>id</b></div>
        <div class="col-10"><b>Email</b></div>
        <div class="col-1"></div>
    </div>

    @forelse ($subscribers as $subscriber)
        @include('admin.subscribers.item')
    @empty
        <div class="container pt-4">Нет подписчиков</div>
    @endforelse

   @includeWhen($paginator->isNeed(), 'pagination.links', ['items' => $subscribers])

@endsection
