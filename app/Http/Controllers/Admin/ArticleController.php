<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticle;
use App\Http\Requests\UpdateArticle;
use App\Service\CustomPaginator;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:author');
    }

    public function index(CustomPaginator $paginator)
    {
        $articles = $paginator->paginate(Article::latest());

        return view('admin.articles.index', compact('articles', 'paginator'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(StoreArticle $request)
    {
        $attributes = $request->validated();
        $attributes['body'] = $request->tag($attributes['body']);

        $article = Article::create($attributes);
        $article->saveImage($request->storeFile('public/articles'));

        flash('Статья успешно создана');
        return redirect()->route('admin.articles.index');
    }

    public function show(Article $article)
    {
        $article->load([
            'comments',
            'comments.user',
        ]);

        return view('admin.articles.show', [
            'article'  => $article,
            'previous' => $this->getShowRoute($article->previous()),
            'next'     => $this->getShowRoute($article->next())
        ]);
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(UpdateArticle $request, Article $article)
    {
        $attributes = $request->validated();
        $attributes['body'] = $request->tag($attributes['body']);

        $article->update($attributes);

        if ($path = $request->storeFile('public/articles')) {
            $article->saveImage($path);
        }

        flash('Статья успешно обновлена');
        return redirect()->route('admin.articles.index');
    }

    public function destroy(Article $article)
    {
        $article->delete();

        flash('Статья удалена', 'danger');
        return back();
    }

    public function setActiveStatus(Article $article)
    {
        return request()->has('active') ? $this->activate($article) : $this->deactivate($article);
    }

    protected function activate(Article $article)
    {
        $article->activate();

        flash('Статья опубликована. Задача "Рассылка всем подписчикам" добавлена в очередь');
        return back();
    }

    protected function deactivate(Article $article)
    {
        $article->deactivate();

        flash('Статья скрыта', 'danger');
        return back();
    }

    protected function getShowRoute($article): ?string
    {
        return $article !== null ? route('admin.articles.show', $article) : null;
    }
}
