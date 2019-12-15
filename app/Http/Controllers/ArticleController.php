<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('image')->active()->latest()->paginate(10);

        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
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
            'previous' => $this->getShowRoute($article->previous()),
            'next'     => $this->getShowRoute($article->next())
        ]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    protected function getShowRoute($article): ?string
    {
        return $article !== null ? route('articles.show', $article) : null;
    }
}
