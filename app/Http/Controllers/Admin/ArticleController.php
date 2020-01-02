<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use App\Http\Controllers\Controller;
use App\Service\CustomPaginator;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:author')->only(['index']);
    }

    public function index(CustomPaginator $paginator)
    {
        $articles = $paginator->paginate(Article::latest());

        return view('admin.articles.index', compact('articles', 'paginator'));
    }

    public function create()
    {
        //
    }

    public function store()
    {
        //
    }

    public function show(Article $article)
    {
        //
    }

    public function edit(Article $article)
    {
        //
    }

    public function update(Article $article)
    {
        //
    }

    public function destroy(Article $article)
    {
        //
    }

    public function activate(Article $article)
    {
        dd(request()->all());
    }
}
