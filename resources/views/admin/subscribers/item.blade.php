<div class="row py-3 border-bottom ml-0 align-items-start">
    <div class="col-1"><b>{{ $subscriber->id }}</b></div>
    <div class="col-10">{{ $subscriber->email }}</div>

    <div class="btn-group">
        @include('layout.modal.delete', ['id' => $subscriber->id, 'route' => route('admin.subscribers.destroy', compact('subscriber'))])
    </div>
</div>
