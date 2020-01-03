<?php

namespace App\Http\Controllers;

use App\Service\CustomPaginator;

class CustomPaginatorController extends Controller
{
    public function perPage(CustomPaginator $paginator)
    {
        return back_with_query($paginator->getQuery());
    }
}
