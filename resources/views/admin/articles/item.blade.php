<div class="row py-3 border-bottom ml-0 align-items-start">
    <div class="col-1"><b>{{ $article->id }}</b></div>
    <div class="col-8"><a href="{{ route('admin.articles.show', compact('article')) }}">{{ $article->title }}</a></div>

    @include('layout.form.active', [
        'path' => route('admin.articles.set-active-status', compact('article')),
        'class' => 'ml-5 mr-auto',
        'checked' => $article->isActive()
    ])

    <div class="btn-group">
        <a class="btn btn-outline-primary rounded-0 btn-sm"
           href="{{ route('admin.articles.edit', compact('article')) }}">Изменить</a>
        @include('layout.modal.delete', ['id' => $article->id, 'route' => route('admin.articles.destroy', compact('article'))])
    </div>

</div>
