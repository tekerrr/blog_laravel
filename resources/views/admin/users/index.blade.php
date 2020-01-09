@extends('layout.admin')

@section('title', 'Пользователи')

@section('content')

    <div class="row pt-3 ml-0 align-items-start">
        @include('pagination.item-per-page-selector')
    </div>

    <div class="row py-3 border-bottom ml-0">
        <div class="col-1"><b>id</b></div>
        <div class="col-4"><b>ФИО</b></div>
        <div class="col-4"><b>Роли</b></div>
        <div class="col-3"></div>
    </div>

    @forelse ($users as $user)
        @include('admin.users.item')
    @empty
        <div class="container pt-4">Нет пользователей</div>
    @endforelse

   @includeWhen($paginator->isNeed(), 'pagination.links', ['items' => $users])

@endsection
