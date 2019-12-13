<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::active()->latest()->paginate(10);

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
        $previous = routeOrNull('articles.show', $article->previous());
        $next = routeOrNull('articles.show', $article->next());

        return view('articles.show', compact('article', 'previous', 'next'));
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
}
