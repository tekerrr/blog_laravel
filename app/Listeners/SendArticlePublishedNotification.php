<?php

namespace App\Listeners;

use App\Events\ArticlePublished;
use App\Subscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\InteractsWithQueue;

class SendArticlePublishedNotification
{
    public function handle(ArticlePublished $event)
    {
        Subscriber::all()->map->notify(new \App\Notifications\ArticlePublished($event->article));
    }
}
