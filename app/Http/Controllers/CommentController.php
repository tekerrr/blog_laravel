<?php

namespace App\Http\Controllers;

class CommentController extends Controller
{
    public function store(\App\Article $article)
    {
        $attributes = request()->validate(['body' => 'required']);
        $attributes['user_id'] = auth()->id();
        $attributes['is_active'] = ($isStuff = auth()->user()->isStuff());

        $article->comments()->create($attributes);

        flash('Комментарий добавлен.' . ($isStuff ? '' : ' Он будет опубликован после модерации.'));

        return back();
    }
}
