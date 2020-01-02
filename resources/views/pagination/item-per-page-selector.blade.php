<form class="ml-auto" method="POST" action="{{ route('admin.per_page') }}">
    @csrf
    @method('PATCH')

    <div class="btn-group">
        <label class="col-9 align-self-end" for="items">Количество на странице</label>
        <select class="form-control rounded-0 " name="items" id="items" onchange="form.submit()">
            @foreach ($paginator->getSelectorOptions() as $key => $value)
                    <option value="{{ $value }}"  {{ $paginator->getPerPage() == $value ? 'selected' : '' }}>{{ $key }}</option>
            @endforeach
        </select>
    </div>
</form>
