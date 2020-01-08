<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('image')->active()->latest()->paginate(config('content.paginator.items'));

        return view('articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
        abort_if(! $article->isActive(), 404);

        $article->load([
            'comments' => function ($query) {
                $query->active();
            },
            'comments.user',
        ]);

        return view('articles.show', [
            'article'  => $article,
            'previous' => $this->getShowRoute($article->previousActive()),
            'next'     => $this->getShowRoute($article->nextActive())
        ]);
    }

    protected function getShowRoute($article): ?string
    {
        return $article !== null ? route('articles.show', $article) : null;
    }
}
