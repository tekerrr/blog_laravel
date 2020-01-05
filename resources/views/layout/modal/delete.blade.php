<!-- Button trigger modal -->
<button type="button" class="btn btn-outline-danger rounded-0 btn-sm" data-toggle="modal" data-target="#modal_{{ $id }}">
    Удалить
</button>

<!-- Modal -->
<div class="modal fade" id="modal_{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Удаление</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Вы точно уверены? Действие необратимо!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Отмена</button>
                <form class="form" action="{{ $route }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="submit" class="btn btn-outline-danger rounded-0" value="Удалить">
                </form>
            </div>
        </div>
    </div>
</div>
