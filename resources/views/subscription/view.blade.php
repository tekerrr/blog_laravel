<div class="row my-4 px-0">
    <div class="ml-auto col-5">
        <form class="form" action="{{ route('subscribe') }}" method="post">
            @csrf

            <div class="input-group">
                @auth
                    <input class="form-control rounded-0" value="{{ auth()->user()->email }}" readonly>
                @else
                    <input type="email"
                           name="email"
                           placeholder="Enter email"
                           class="form-control rounded-0  @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                    >
                @endauth
                <input type="submit" name="#" class="btn btn-primary btn-md rounded-0" value="Подписаться на рассылку">
                @include('layout.input.error', ['name' => 'email'])
            </div>
        </form>
    </div>
</div>
