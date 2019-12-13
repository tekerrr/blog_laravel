@extends('layout.master')

@section('title', 'Регистрация')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height:80vh">
            <form class="form col-5" action="{{ route('register') }}" method="POST">

                @csrf

                <h3 class="text-center text-primary">Регистрация</h3>

                @include('layout.input.input', ['name' => 'email', 'type' => 'email', 'label' => 'Email:', 'addition' => 'autocomplete="username"'])
                @include('layout.input.input', ['name' => 'name', 'label' => 'Имя:'])
                @include('layout.input.input', ['name' => 'password', 'type' => 'password', 'label' => 'Пароль:', 'addition' => 'autocomplete="new-password"'])
                @include('layout.input.input', ['name' => 'password_confirmation', 'type' => 'password', 'label' => 'Повторите пароль:', 'addition' => 'autocomplete="new-password"'])


                <div class="row form-group mx-0">
                    <input type="submit" class="btn btn-primary btn-md rounded-0" value="Зарегистрироваться">
                    <a class="btn btn-outline-primary rounded-0 ml-auto" href="{{ route('login') }}">
                        Войти
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
