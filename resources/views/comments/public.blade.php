<h3 class="py-3">Комментарии</h3>

@forelse ($comments as $comment)
    <div class="media py-3">
        <div class="col-1 mr-3">
            <img src="{{ $comment->user->getImageUrl() ?? '/img/default-avatar.png' }}" alt="post img" class="img-fluid">
        </div>
        <div class="media-body py-0">
            <h4>{{ $comment->user->name }}</h4>
            <p><i>{{ $comment->created_at }}</i></p>
            <p>{{ $comment->body }}</p>
        </div>
    </div>
@empty
    <p>Отсутствуют... Будьте первым!</p>
@endforelse
