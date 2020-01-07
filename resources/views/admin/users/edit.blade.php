@extends('layout.master')

@section('title', 'Редактирование пользователя')

@section('content')
    <div class="container my-4">
        <h1 class="text text-center pb-5">Редактирование пользователя</h1>

        <div class="row justify-content-center">

            <!--    Avatar    -->
            <div class="col-4">
                <img src="{{ $user->getImageUrl() ?? '/img/default-avatar.png' }}" alt="post img" class="img-fluid ">
                <div class="row py-3">
                    <form class="form col-6 px-0" enctype="multipart/form-data" action="{{ route('admin.avatar.update', compact('user')) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <label class="btn btn-primary rounded-0 mx-3 my-0 @error('avatar') is-invalid @enderror">
                            Загрузить аватар <input type="file" name="avatar" onchange="form.submit()" hidden>
                        </label>
                        @include('layout.input.error', ['name' => 'avatar'])
                    </form>
                    <form class="ml-0" method="POST" action="{{ route('admin.avatar.destroy', compact('user')) }}">
                        @csrf
                        @method('DELETE')
                        <input type="submit" class="btn btn-outline-primary rounded-0" value="Удалить">
                    </form>
                </div>
            </div>

            <div class="col-4">
                <!--    User Data    -->
                <form class="form" action="{{ route('admin.users.update', compact('user')) }}" method="POST">
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

            </div>

        </div>

        @foreach($roles as $role)
            <div class="row py-3 border-bottom ml-0 align-items-start w-50">
                <div class="col-4"><b>{{ $role->role }}</b></div>
                <form method="post" action="{{ route('admin.users.roles.' . ($role->isActive ? 'remove' : 'add'), compact('user', 'role')) }}">
                    @csrf
                    @method('PATCH')
                    <text class ="text-{{ $role->isActive ? 'success' : 'danger' }}">{{ $role->isActive ? 'Состоит' : 'Не состоит' }}</text>
                    <input type="submit" class="btn btn-outline-primary rounded-0 btn-sm  ml-1" value="Сменить">
                </form>
            </div>
        @endforeach

    </div>
@endsection
