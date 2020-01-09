<form class="{{ $class }}" method="POST" action="{{ $path }}">
    @csrf
    @method('PATCH')

    @include('layout.input.toggle', compact('checked'))
</form>
