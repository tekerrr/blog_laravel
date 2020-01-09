<div class="row py-3 border-bottom ml-0 align-items-start">
    <div class="col-1"><b>{{ $user->id }}</b></div>
    <div class="col-4">{{ $user->name }}</div>
    <div class="col-4">{{ $user->getRoles(true) }}</div>

    @include('layout.form.active', [
        'path' => route('admin.users.set-active-status', compact('user')),
        'class' => 'ml-5 mr-auto',
        'checked' => $user->isActive()
    ])

    <div class="btn-group">
        <a class="btn btn-outline-primary rounded-0 btn-sm"
           href="{{ route('admin.users.edit', compact('user')) }}">Изменить</a>
        @include('layout.modal.delete', ['id' => $user->id, 'route' => route('admin.users.destroy', compact('user'))])
    </div>

</div>
