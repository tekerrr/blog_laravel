@extends('layout.admin')

@section('title', 'Добавить статью')

@section('body')

    <div class="container">
        <form class="form pt-4" enctype="multipart/form-data" action="{{ route('admin.articles.store') }}" method="POST">

            @csrf

            <h3 class="text-center text-primary">Добавить статью</h3>

            @include('layout.input.input', ['name' => 'title', 'label' => 'Заголовок:'])
            @include('layout.input.area', ['name' => 'abstract', 'label' => 'Краткое описание (поддерживает теги)'])
            @include('layout.input.area', ['name' => 'body', 'label' => 'Содержимое (поддерживает теги):', 'rows' => '10'])
            @include('layout.input.checkbox', ['name' => 'paragraph_tag', 'label' => 'Заключить каждый абзац в тэг <p>', 'divClass' => 'mb-3'])
            @include('layout.input.file', ['name' => 'image', 'label' => 'Изображение:', 'divClass' => 'mb-3'])
            @include('layout.input.confirm')
        </form>
    </div>

@endsection
