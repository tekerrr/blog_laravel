<div class="row py-3 border-bottom ml-0 align-items-start">
    <div class="col-1"><b>{{ $article->id }}</b></div>
    <div class="col-8"><a href="{{ route('admin.articles.show', compact('article')) }}">{{ $article->title }}</a></div>

    <form class="ml-5 mr-auto" method="POST" action="{{ route('admin.article.activate', compact('article')) }}">
        @csrf
        @method('PATCH')

        <input type="checkbox" data-toggle="toggle" {{ $article->isActive() ? 'checked' : '' }}
               class="mx-auto" data-onstyle="outline-primary" data-offstyle="outline-danger" data-style="squared" data-size="sm"
               onchange="form.submit()" name="activate">
    </form>

    <div class="btn-group">
        <a class="btn btn-outline-primary rounded-0 btn-sm"
           href="{{ route('admin.articles.edit', compact('article')) }}">Изменить</a>
        @include('layout.modal.delete', ['id' => $article->id, 'route' => route('admin.articles.destroy', compact('article'))])
    </div>

</div>
