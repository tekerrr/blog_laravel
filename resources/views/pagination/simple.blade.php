<div class="btn-toolbar my-4 justify-content-between" role="toolbar" aria-label="Toolbar with button groups">

    <div class="btn-group" role="group" aria-label="First group">
        @if ($previous)
            <a class="btn btn-outline-primary rounded-0" href="{{ $previous }}"><--</a>
        @else
            <a class="btn btn-outline-secondary rounded-0 disabled"><--</a>
        @endif
    </div>

    <div class="btn-group" role="group" aria-label="First group">
        @if ($next)
            <a class="btn btn-outline-primary rounded-0" href="{{ $next }}">--></a>
        @else
            <a class="btn btn-outline-secondary rounded-0 disabled">--></a>
        @endif
    </div>

</div>
