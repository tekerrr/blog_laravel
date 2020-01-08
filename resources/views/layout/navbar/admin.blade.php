<nav class="navbar sticky-top navbar-expand-md navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand mr-2" href="{{ route('articles.index') }}">
            <img src="/img/ico.png" class="d-inline-block align-top" alt="icon">
            MyBlog
        </a>
        <a class="badge badge-success" href="{{ route('admin.articles.index') }}">
            admin
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
            <ul class="navbar-nav">
                @include('layout.navbar.items.item', ['name' => 'Статьи', 'path' => route('admin.articles.index'), 'active' => is_current_route('admin.articles.index')])
                @include('layout.navbar.items.item', ['name' => 'Комментарии', 'path' => route('admin.comments.index'), 'active' => is_current_route('admin.comments.index')])
                @include('layout.navbar.items.item', ['name' => 'Страницы', 'path' => route('admin.pages.index'), 'active' => is_current_route('admin.pages.index')])

                @includeWhen(auth()->user()->isAdmin(), 'layout.navbar.items.dropdown-users', ['active' => (is_current_route('admin.users.index') || is_current_route('admin.subscribers.index'))])

                @includeWhen(auth()->user()->isAdmin(), 'layout.navbar.items.item', ['name' => 'Настройки', 'path' => route('admin.settings.edit'), 'active' => is_current_route('admin.settings.edit')])

                @include('layout.navbar.items.auth-button-admin')
            </ul>
        </div>
    </div>
</nav>
