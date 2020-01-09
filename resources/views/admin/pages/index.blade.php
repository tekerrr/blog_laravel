@extends('layout.admin')

@section('title', 'Статичные страницы')

@section('content')

    @include('admin.content-header', ['create_path' => route('admin.pages.create')])

    <div class="row py-3 border-bottom ml-0">
        <div class="col-1"><b>id</b></div>
        <div class="col-8"><b>Название</b></div>
        <div class="col-3"></div>
    </div>

    @forelse ($pages as $page)
        @include('admin.pages.item')
    @empty
        <div class="container pt-4">Нет страниц</div>
    @endforelse

   @includeWhen($paginator->isNeed(), 'pagination.links', ['items' => $pages])

@endsection
