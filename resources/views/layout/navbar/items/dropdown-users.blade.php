<li class="dropdown">
    <a class="nav-link dropdown-toggle {{ $active ? 'active' : '' }}" href="" data-toggle="dropdown">Users</a>
    <div class="dropdown-menu">
        @include('layout.navbar.items.dropdown-item', ['name' => 'Пользователи', 'path' => route('admin.users.index')])
        @include('layout.navbar.items.dropdown-item', ['name' => 'Подписчики', 'path' => route('admin.subscribers.index')])
    </div>
</li>
