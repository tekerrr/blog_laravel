<li class="dropdown">
    <a class="nav-link dropdown-toggle ml-3 {{ is_current_route('account.edit') ? 'active' : '' }}"
       href="#" data-toggle="dropdown">{{ auth()->user()->name }}</a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{ route('account.edit') }}">Профиль</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('articles.index') }}">Основной сайт</a>
        <div class="dropdown-divider"></div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <input type="submit" class="dropdown-item" value="Выйти">
        </form>
    </div>
</li>
