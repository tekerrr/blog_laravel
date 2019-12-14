<nav class="navbar sticky-top navbar-expand-md navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ route('articles.index') }}">
            <img src="/img/ico.png" class="d-inline-block align-top" alt="icon">
            MyBlog
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
            <ul class="navbar-nav">
                @include('layout.navbar.item', ['name' => 'Главная', 'path' => route('articles.index'), 'active' => false])
                @include('layout.navbar.dropdown-articles', ['article' => $articles, 'active' => true])
                @include('layout.navbar.auth-button')
            </ul>
        </div>
    </div>
</nav>
