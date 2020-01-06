<div class="row py-3 border-bottom ml-0 align-items-start">

    <div class="col-1"><b>{{ $page->id }}</b></div>
    <div class="col-8"><a href="{{ route('admin.pages.show', compact('page')) }}">{{ $page->title }}</a></div>

    @include('layout.form.active', [
        'path' => route('admin.pages.set-active-status', compact('page')),
        'class' => 'ml-5 mr-auto',
        'checked' => $page->isActive()
    ])

    <div class="btn-group">
        <a class="btn btn-outline-primary rounded-0 btn-sm"
           href="{{ route('admin.pages.edit', compact('page')) }}">Изменить</a>
        @include('layout.modal.delete', ['id' => $page->id, 'route' => route('admin.pages.destroy', compact('page'))])
    </div>

</div>
