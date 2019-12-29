<?php

namespace App\Http\Controllers;

use App\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('destroy');
    }

    public function subscribe()
    {
        if (auth()->check()) {
            auth()->user()->subscription()->create();
        } else {
            $attributes = request()->validate(['email' => ['required', 'string', 'email', 'max:255']]);
            Subscriber::firstOrCreate($attributes);
        }

        flash('Подписка успешно оформлена.');
        return back();
    }

    public function unsubscribe()
    {
        auth()->user()->subscription()->delete();

        flash('Вы отписались от получения сообщений о новых статьях.', 'danger');
        return back();
    }

    public function unsubscribeByLink(Subscriber $subscriber)
    {
        if (! request()->hasValidSignature()) {
            abort(403);
        }

        $subscriber->delete();
        flash('Вы отписались от получения сообщений о новых статьях.', 'danger');
        return redirect()->route('articles.index');
    }
}
