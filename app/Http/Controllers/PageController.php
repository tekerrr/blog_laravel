<?php

namespace App\Http\Controllers;

use App\Page;

class PageController extends Controller
{
    public function show(Page $page)
    {
        abort_if(! $page->isActive(), 404);

        return view ('pages.show', compact('page'));
    }
}
