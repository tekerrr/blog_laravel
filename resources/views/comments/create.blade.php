@auth
    <div class="border-top py-4">
        <form class="form" action="{{ route('comments.store', $article) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="add_comment" class="h4">Оставить комментарий</label>
                <textarea name="body"
                          id="add_comment"
                          class="form-control rounded-0 @error('body') is-invalid @enderror"
                          rows="2"
                          placeholder="Комментрарий" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Комментрарий'"
                >{{ old('body') }}</textarea>
                @include('layout.input.error', ['name' => 'body'])
            </div>

            <div class="row mx-0 pt-4">
                <input type="submit" class="btn btn-outline-primary btn-md rounded-0 ml-auto" value="Отправить">
            </div>
        </form>
    </div>
@else
    <h4 class="border-top pt-4">Оставить комментарий</h4>
    <p>Для отравки комментария необходимо авторизироваться.</p>
@endauth
