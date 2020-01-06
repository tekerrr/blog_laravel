<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePage;
use App\Page;
use App\Service\CustomPaginator;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:author');
    }

    public function index(CustomPaginator $paginator)
    {
        $pages = $paginator->paginate(Page::latest());

        return view('admin.pages.index', compact('pages', 'paginator'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(StorePage $request)
    {
        $attributes = $request->validated();
        $attributes['body'] = $request->tag($attributes['body']);

        Page::create($attributes);

        flash('Страница успешно создана');
        return redirect()->route('admin.pages.index');
    }

    public function show(Page $page)
    {
        return view ('admin.pages.show', compact('page'));
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(StorePage $request, Page $page)
    {
        $attributes = $request->validated();
        $attributes['body'] = $request->tag($attributes['body']);

        $page->update($attributes);

        flash('Страница успешно обновлена');
        return redirect()->route('admin.pages.index');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        flash('Страница удалена', 'danger');
        return back();
    }

    public function setActiveStatus(Page $page)
    {
        return request()->has('active') ? $this->activate($page) : $this->deactivate($page);
    }

    protected function activate(Page $page)
    {
        $page->activate();

        flash('Страница опубликована');
        return back();
    }

    protected function deactivate(Page $page)
    {
        $page->deactivate();

        flash('Страница скрыта', 'danger');
        return back();
    }
}
