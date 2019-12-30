<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\CustomPaginator;

class AdminController extends Controller
{
    public function perPage(CustomPaginator $paginator)
    {
        return back_with_query($paginator->getQuery());
    }
}
