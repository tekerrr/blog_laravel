@extends('layout.admin')

@section('title', 'Добавить статичную страницу')

@section('body')

    <div class="container">
        <form class="form pt-4" enctype="multipart/form-data" action="{{ route('admin.pages.store') }}" method="POST">

            @csrf

            <h3 class="text-center text-primary">Добавить статичную страницу</h3>

            @include('layout.input.input', ['name' => 'title', 'label' => 'Заголовок:'])
            @include('layout.input.area', ['name' => 'body', 'label' => 'Содержимое (поддерживает теги):', 'rows' => '10'])
            @include('layout.input.checkbox', ['name' => 'paragraph_tag', 'label' => 'Заключить каждый абзац в тэг <p>', 'divClass' => 'mb-3'])
            @include('layout.input.confirm')

        </form>
    </div>

@endsection
