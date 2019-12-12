<div class="row my-4 px-0">
    <div class="ml-auto col-5">
        <form class="form" action="" method="post">

            @csrf

            <div class="input-group">
                @auth
                    <input type="email" name="email" class="form-control rounded-0"
                           value="{{ auth()->user()->email }}" readonly>
                @else
                    <input type="email" name="email" placeholder="Enter email"
                           class="form-control rounded-0  {{ $status ?? '' }}"
                           value="{{ old('email') }}">
                @endauth

                <input type="submit" name="#" class="btn btn-primary btn-md rounded-0"
                       value="Подписаться на рассылку">
                <div class="invalid-feedback text-left">{{ $message ?? 'Ошибка при заполнении поля' }}</div>
            </div>
        </form>
    </div>
</div>
