<li class="dropdown">
    <a class="nav-link dropdown-toggle {{ $active ? 'active' : '' }}?>"
       href="" data-toggle="dropdown">{{ $name }}</a>
    <div class="dropdown-menu">
        @foreach ($items as $item)
            @include('layout.navbar.dropdown-item', ['title' => $item['title'], 'path' => $item['path']])
        @endforeach

        @if ($lastItem)
            <div class="dropdown-divider"></div>
            @include('layout.navbar.dropdown-item', ['title' => $lastItem['title'], 'path' => $lastItem['path']])
        @endif
    </div>
</li>
