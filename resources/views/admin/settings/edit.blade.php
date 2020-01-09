@extends('layout.admin')

@section('title', 'Настройки')

@section('content')

    <div class="container">
        <form class="form pt-4" enctype="multipart/form-data" action="{{ route('admin.settings.update') }}" method="POST">

            @csrf
            @method('PATCH')

            @include('layout.input.settings.input', ['name' => 'paginator_items', 'label' => 'Количество статей на Главной странице', 'default' => data_get($settings, 'paginator.items')])
            @include('layout.input.settings.input', ['name' => 'navbar_articles', 'label' => 'Количество статей на в Панели навигации', 'default' => data_get($settings, 'navbar.articles')])

            <div class="form-row align-items-center pt-2">
                <label class="col-6" for="custom_paginator_items">Количество элементов на страницах "Административного раздела"</label>
                <div class="col-1">
                    <select class="form-control rounded-0" name="custom_paginator_items" id="custom_paginator_items">
                        @foreach (data_get($settings, 'custom_paginator.options') as $key => $value)
                            <option value="{{ $value }}"  {{ data_get($settings, 'custom_paginator.items') == $value ? 'selected' : '' }}>{{ $key }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row form-group mx-0 pt-2">
                <input type="submit" class="btn btn-primary btn-md rounded-0" value="Сохранить">
            </div>

        </form>
    </div>

@endsection
