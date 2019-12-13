@extends('layout.master')

@section('title', 'Авторизация')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height:70vh">
            <form class="form col-5" action="{{ route('login') }}" method="POST">

                @csrf

                <h3 class="text-center text-primary">Авторизация</h3>

                @include('layout.input.input', ['name' => 'email', 'type' => 'email', 'label' => 'Email:', 'addition' => 'autocomplete="username"'])
                @include('layout.input.input', ['name' => 'password', 'type' => 'password', 'label' => 'Пароль:', 'addition' => 'current-password"'])

                <div class="row form-group mx-0">
                    <input type="submit" class="btn btn-primary btn-md rounded-0" value="Войти">
                    <a class="btn btn-outline-primary rounded-0 ml-auto" href="{{ route('register') }}">
                        Зарегистрироваться
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
