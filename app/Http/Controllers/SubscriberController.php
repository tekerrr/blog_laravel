<?php

namespace App\Http\Controllers;

use App\Subscriber;

class SubscriberController extends Controller
{
    public function subscribe()
    {
        auth()->check()
            ? $this->subscribeForAuth()
            : $this->subscribeForGuest();

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

    protected function subscribeForAuth()
    {
        if (! auth()->user()->subscription) {
            auth()->user()->subscription()->create();
        }
    }

    protected function subscribeForGuest()
    {
        $attributes = request()->validate(['email' => ['required', 'string', 'email', 'max:255']]);
        Subscriber::firstOrCreate($attributes);
    }
}
