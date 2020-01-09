@extends('layout.admin')

@section('title', $page->title)

@section('body')
    <div class="container my-5">
        <div class="row justify-content-center my-3">
            <h1>{{ $page->title }}</h1>
        </div>
        {!! $page->body !!}
    </div>
@endsection
