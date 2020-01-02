@extends('layout.master')

@section('navbar')
    @include('layout.navbar.admin')
@endsection

@section('body')
    <div class="container">

        <div class="row justify-content-center my-3">
            <h1>
                @yield('content_title', \Illuminate\Support\Facades\View::yieldContent('title'))
            </h1>
        </div>

        @yield('content')

    </div>
@endsection
