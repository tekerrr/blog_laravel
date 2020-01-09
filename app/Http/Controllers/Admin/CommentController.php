<?php

namespace App\Http\Controllers\Admin;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Service\CustomPaginator;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(CustomPaginator $paginator)
    {
        $comments = $paginator->paginate(Comment::with('article')->latest());

        return view('admin.comments.index', compact('comments', 'paginator'));
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        flash('Комментарий удален', 'danger');
        return back();
    }

    public function setActiveStatus(Comment $comment)
    {
        return request()->has('active') ? $this->activate($comment) : $this->deactivate($comment);
    }

    protected function activate(Comment $comment)
    {
        $comment->activate();

        flash('Комментарий опубликован');
        return back();
    }

    protected function deactivate(Comment $comment)
    {
        $comment->deactivate();

        flash('Комментарий скрыт', 'danger');
        return back();
    }
}
