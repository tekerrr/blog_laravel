@extends('layout.admin')

@section('title', 'Редактировать статичную страницу')

@section('body')

    <div class="container">
        <form class="form pt-4" enctype="multipart/form-data" action="{{ route('admin.pages.update', compact('page')) }}" method="POST">

            @csrf
            @method('PATCH')

            <h3 class="text-center text-primary">Редактировать статичную страницу</h3>

            @include('layout.input.input', ['name' => 'title', 'label' => 'Заголовок:', 'default' => $page->title])
            @include('layout.input.area', ['name' => 'body', 'label' => 'Содержимое (поддерживает теги):', 'rows' => '10', 'default' => $page->body])
            @include('layout.input.checkbox', ['name' => 'paragraph_tag', 'label' => 'Заключить каждый абзац в тэг <p>', 'divClass' => 'mb-3'])
            @include('layout.input.confirm', ['buttonText' => 'Изменить'])

        </form>
    </div>

@endsection
