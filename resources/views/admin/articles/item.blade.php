<div class="row py-3 border-bottom ml-0 align-items-start">
    <div class="col-1"><b>{{ $article->id }}</b></div>
    <div class="col-8"><a href="{{ route('admin.articles.show', compact('article')) }}">{{ $article->title }}</a></div>

    <div class="btn-group ml-3 mr-5">
        <input type="checkbox" data-toggle="toggle" {{ $article->isActive() ? 'checked' : '' }}
               class="mx-auto" data-onstyle="outline-primary" data-offstyle="outline-danger" data-style="squared" data-size="sm">
    </div>

    <form class="form" action="{{ route('admin.articles.destroy', compact('article')) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="btn-group mr-auto">
            <a class="btn btn-outline-primary rounded-0 btn-sm"
               href="{{ route('admin.articles.edit', compact('article')) }}">Изменить</a>
            <input type="submit" class="btn btn-outline-danger rounded-0 btn-sm" value="Удалить">
        </div>
    </form>

</div>
