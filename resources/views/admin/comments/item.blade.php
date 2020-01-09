<div class="row py-3 border-bottom ml-0 align-items-start">
    <div class="col-1"><b>{{ $comment->id }}</b></div>
    <div class="col-1">
        <a href="{{ route('admin.articles.show', ['article' => $comment->article])}}">{{ $comment->article->id }}</a>
    </div>
    <div class="col-8">
        {{ $comment->body }}
    </div>

    @include('layout.form.active', [
        'path' => route('admin.comments.set-active-status', compact('comment')),
        'class' => 'ml-5 mr-auto',
        'checked' => $comment->isActive()
    ])

    <div class="btn-group">
        @include('layout.modal.delete', ['id' => $comment->id, 'route' => route('admin.comments.destroy', compact('comment'))])
    </div>

</div>
