<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\CustomPaginator;
use App\Subscriber;

class SubscriberController extends Controller
{
    public function index(CustomPaginator $paginator)
    {
        $subscribers = $paginator->paginate(Subscriber::latest());

        return view('admin.subscribers.index', compact('subscribers', 'paginator'));
    }

    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        flash('Подписчик удален', 'danger');
        return back();
    }
}
