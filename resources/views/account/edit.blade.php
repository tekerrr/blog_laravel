@extends('layout.master')

@section('title', 'Личный кабинет')

@section('content')
    <div class="container my-4">
        <h1 class="text text-center pb-5">Личный кабинет</h1>

        <div class="row justify-content-center">

            <!--    Avatar    -->
            <div class="col-4">
                <img src="{{ $user->getImageUrl() ?? '/img/default-avatar.png' }}" alt="post img" class="img-fluid ">
                <div class="row py-3">
                    <form class="form col-6 px-0" enctype="multipart/form-data" action="{{ route('avatar.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <label class="btn btn-primary rounded-0 mx-3 my-0 @error('avatar') is-invalid @enderror">
                            Загрузить аватар <input type="file" name="avatar" onchange="form.submit()" hidden>
                        </label>
                        @include('layout.input.error', ['name' => 'avatar'])
                    </form>
                    <form class="ml-0" method="POST" action="{{ route('avatar.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <input type="submit" class="btn btn-outline-primary rounded-0" value="Удалить">
                    </form>
                </div>
            </div>

            <div class="col-4">
                <!--    User Data    -->
                <form class="form" action="{{ route('account.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    @include('layout.input.input', [
                        'name'       => 'email',
                        'type'       => 'email',
                        'label'      => 'Email',
                        'addition'   => 'autocomplete="username"',
                        'class'      => 'col',
                        'labelClass' => 'col-3 align-self-end',
                        'divClass'   => 'row justify-content-start',
                        'default'    => $user->email
                    ])

                    @include('layout.input.input', [
                        'name'       => 'name',
                        'label'      => 'Имя',
                        'class'      => 'col',
                        'labelClass' => 'col-3 align-self-end',
                        'divClass'   => 'row justify-content-start',
                        'default'    => $user->name
                    ])

                    <div class="form-group row justify-content-end">
                        <text class="text-primary col align-self-center">Пароль</text>
                        <a class="btn btn-outline-secondary rounded-0 col-4 border" href="{{ route('password.edit') }}">Сменить</a>
                    </div>

                    @include('layout.input.area', [
                        'name'       => 'about',
                        'label'      => 'О себе',
                        'class'      => 'col',
                        'labelClass' => 'col-3 pt-1',
                        'divClass'   => 'row',
                        'default'    => $user->about
                    ])

                    <div class="form-group row">
                        <div class="col"></div>
                        <input type="submit" class="btn btn-primary btn-md rounded-0" value="Сохранить">
                    </div>
                </form>

                <!--    Subscribe    -->
                @if ($user->isSubscriber())
                    <form class="form row form-group" action="{{ route('subscriber.destroy') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <label for="unsubscribe" class="text-primary col align-self-end">Подписка оформлена</label>
                        <input type="submit" class="btn btn-outline-danger btn-md rounded-0 col-4" id="unsubscribe" value="Отписаться">
                    </form>
                @else
                    <form class="form row form-group" action="{{ route('subscriber.store') }}" method="POST">
                        @csrf
                        <label for="subscribe" class="text-primary col align-self-end">Подписка на новые статьи</label>
                        <input type="submit" class="btn btn-primary btn-md rounded-0 col-4" id="subscribe" value="Оформить">
                    </form>
                @endif

            </div>

        </div>

    </div>
@endsection
