@extends('layout.admin')

@section('title', 'Редактировать статью')

@section('body')

    <div class="container">
        <form class="form pt-4" enctype="multipart/form-data" action="{{ route('admin.articles.update', compact('article')) }}" method="POST">

            @csrf
            @method('PATCH')

            <h3 class="text-center text-primary">Редактировать статью</h3>

            @include('layout.input.input', ['name' => 'title', 'label' => 'Заголовок:', 'default' => $article->title])
            @include('layout.input.area', ['name' => 'abstract', 'label' => 'Краткое описание (поддерживает теги)', 'default' => $article->abstract])
            @include('layout.input.area', ['name' => 'body', 'label' => 'Содержимое (поддерживает теги):', 'rows' => '10', 'default' => $article->body])
            @include('layout.input.checkbox', ['name' => 'paragraph_tag', 'label' => 'Заключить каждый абзац в тэг <p>', 'divClass' => 'mb-3'])

            <div class="mb-3 w-50 mt-3">
                <div class="text-primary">Изображение: </div>
                <img src="{{ $article->getImageUrl() ?? '/img/article/default.svg' }}" alt="post img" class="img-fluid">
            </div>

            @include('layout.input.file', ['name' => 'image', 'label' => 'Для изменеия загрузите новый файл::', 'divClass' => 'mb-3'])
            @include('layout.input.confirm', ['buttonText' => 'Изменить'])

        </form>
    </div>

@endsection
