<li class="dropdown">
    <a class="nav-link dropdown-toggle {{ $active ? 'active' : '' }}" href="" data-toggle="dropdown">Статьи</a>
    <div class="dropdown-menu">
        @foreach ($articles as $article)
            @include('layout.navbar.items.dropdown-item', ['name' => $article->title, 'path' => route('articles.show', $article)])
        @endforeach
        <div class="dropdown-divider"></div>
        @include('layout.navbar.items.dropdown-item', ['name' => 'Все', 'path' => route('articles.index')])
    </div>
</li>
