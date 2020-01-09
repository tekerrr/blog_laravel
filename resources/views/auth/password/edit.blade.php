@extends('layout.master')

@section('title', 'Смена пароля')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height:70vh">
            <form class="form col-5" action="" method="POST">

                @csrf
                @method('PATCH')

                <h3 class="text-center text-primary">Смена пароля</h3>

                @include('layout.input.input', ['name' => 'current-password', 'type' => 'password', 'label' => 'Старый:', 'addition' => 'autocomplete="current-password"'])
                @include('layout.input.input', ['name' => 'password', 'type' => 'password', 'label' => 'Новый:', 'addition' => 'autocomplete="new-password"'])
                @include('layout.input.input', ['name' => 'password_confirmation', 'type' => 'password', 'label' => 'Повторите пароль:', 'addition' => 'autocomplete="new-password"'])

                <div class="row form-group mx-0">
                    <input type="submit" class="btn btn-primary btn-md rounded-0" value="Изменить">
                    <a class="btn btn-outline-primary rounded-0 ml-auto" href="{{ route('account.edit') }}">Назад</a>
                </div>
            </form>
        </div>
    </div>
@endsection
